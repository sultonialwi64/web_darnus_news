<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile() // Mengaktifkan fitur Ganti Password & Edit Profil
            ->brandName('DMN NEWS')
            ->brandLogo(null)
            ->sidebarWidth('14rem')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->navigationGroups([
                'Konten Berita',
                'Sistem',
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <style>
                        .fi-sidebar-item-label {
                            white-space: normal !important;
                            line-height: 1.25 !important;
                            padding-top: 0.25rem !important;
                            padding-bottom: 0.25rem !important;
                        }
                        
                        /* Mobile-only safe wrap for RichEditor Toolbar */
                        @media (max-width: 768px) {
                            .fi-fo-rich-editor-toolbar,
                            .fi-fo-rich-editor-toolbar > div,
                            .fi-fo-rich-editor-toolbar ul {
                                flex-wrap: wrap !important;
                            }
                        }

                        /* Auto-save Indicator */
                        #autosave-indicator {
                            position: fixed;
                            bottom: 1.25rem;
                            right: 1.25rem;
                            z-index: 9999;
                            display: flex;
                            align-items: center;
                            gap: 0.4rem;
                            padding: 0.4rem 0.85rem;
                            border-radius: 999px;
                            font-size: 0.72rem;
                            font-weight: 600;
                            letter-spacing: 0.02em;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
                            opacity: 0;
                            transition: opacity 0.4s ease;
                            pointer-events: none;
                        }
                        #autosave-indicator.show { opacity: 1; }
                        #autosave-indicator.saving { background: #1e293b; color: #94a3b8; }
                        #autosave-indicator.saved  { background: #14532d; color: #86efac; }
                        #autosave-indicator.error  { background: #7f1d1d; color: #fca5a5; }
                    </style>
                '),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): HtmlString => new HtmlString('
                    <!-- Auto-Save Indicator Badge -->
                    <div id="autosave-indicator" class="saving">
                        <span id="autosave-icon">⏳</span>
                        <span id="autosave-text">Menyimpan draft...</span>
                    </div>

                    <script>
                    (function () {
                        // Hanya aktif di halaman edit/create berita (Post & DraftPost)
                        const path = window.location.pathname;
                        const isPostPage = path.includes(\'/posts/create\') || path.includes(\'/draft-posts/create\') || 
                                         /\/posts\/\d+\/edit/.test(path) || /\/draft-posts\/\d+\/edit/.test(path);
                        if (!isPostPage) return;

                        // Ambil ID berita dari URL (mendukung path /posts/ atau /draft-posts/)
                        const matchId = path.match(/\/(?:posts|draft-posts)\/(\d+)\/edit/);
                        let postId = matchId ? matchId[1] : null;

                        const indicator = document.getElementById(\'autosave-indicator\');
                        const iconEl    = document.getElementById(\'autosave-icon\');
                        const textEl    = document.getElementById(\'autosave-text\');
                        let saveTimer   = null;
                        let isSaving    = false;
                        let hideTimer   = null;

                        function showIndicator(status, message) {
                            indicator.className = \'show \' + status;
                            iconEl.textContent  = status === \'saving\' ? \'⏳\' : (status === \'saved\' ? \'✅\' : \'❌\');
                            textEl.textContent  = message;
                            clearTimeout(hideTimer);
                            if (status !== \'saving\') {
                                hideTimer = setTimeout(() => indicator.classList.remove(\'show\'), 4000);
                            }
                        }

                        function getCsrfToken() {
                            return document.querySelector(\'meta[name="csrf-token"]\')?.content || \'\';
                        }

                        function collectFormData() {
                            const data = {};

                            // Filament merender input dengan id mengikuti pola: "data.title", "data.content", dll
                            // Kita cari semua input/textarea yang ada di form
                            const allInputs = document.querySelectorAll(\'input[id], textarea[id]\');
                            allInputs.forEach(function(el) {
                                const id = el.id || \'\';
                                if (id.includes(\'title\') && !id.includes(\'og_\') && !id.includes(\'meta_\')) {
                                    data.title = el.value;
                                }
                                if (id.includes(\'excerpt\')) {
                                    data.excerpt = el.value;
                                }
                            });

                            // Ambil konten dari TipTap RichEditor (Filament v3)
                            const proseMirror = document.querySelector(\'.tiptap.ProseMirror, [contenteditable="true"].ProseMirror\');
                            if (proseMirror) {
                                data.content = proseMirror.innerHTML;
                            }

                            return data;
                        }

                        async function doAutoSave() {
                            if (isSaving) return;
                            const formData = collectFormData();
                            if (!formData.title && !formData.content) return; // Skip jika kosong

                            isSaving = true;
                            showIndicator(\'saving\', \'Menyimpan draft...\');

                            const url = postId
                                ? \'/admin-api/auto-save/\' + postId
                                : \'/admin-api/auto-save\';

                            try {
                                const res = await fetch(url, {
                                    method: \'POST\',
                                    headers: {
                                        \'Content-Type\': \'application/json\',
                                        \'X-CSRF-TOKEN\': getCsrfToken(),
                                        \'Accept\': \'application/json\',
                                    },
                                    body: JSON.stringify(formData),
                                    keepalive: true, // Penting! Memastikan request selesai meski tab ditutup
                                });

                                const result = await res.json();
                                if (result.status === \'skipped\') {
                                    // Data kosong, tidak perlu simpan, sembunyikan indicator
                                    indicator.classList.remove(\'show\');
                                } else {
                                    if (result.id && !postId) {
                                        // Kalau ini halaman Create dan baru dapat ID, simpan untuk auto-save berikutnya
                                        postId = result.id;
                                    }
                                    showIndicator(\'saved\', \'Draft tersimpan · \' + (result.savedAt || \'\'));
                                }
                            } catch (e) {
                                showIndicator(\'error\', \'Gagal menyimpan draft\');
                            } finally {
                                isSaving = false;
                            }
                        }

                        // === TRIGGER 1: Auto-save setiap 30 detik ===
                        setInterval(doAutoSave, 30000);

                        // === TRIGGER 2: Saat tab/browser ditutup (jaring pengaman) ===
                        window.addEventListener(\'beforeunload\', function () {
                            const formData = collectFormData();
                            if (!formData.title && !formData.content) return;

                            const url = postId
                                ? \'/admin-api/auto-save/\' + postId
                                : \'/admin-api/auto-save\';

                            // Gunakan sendBeacon untuk request yang lebih andal saat tab ditutup
                            const blob = new Blob([JSON.stringify(formData)], { type: \'application/json\' });
                            navigator.sendBeacon(url + \'?_token=\' + encodeURIComponent(getCsrfToken()), blob);
                        });
                    })();
                    </script>
                '),
            )
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->font('Plus Jakarta Sans')
            ->defaultThemeMode(ThemeMode::Light)
            ->darkMode(true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
