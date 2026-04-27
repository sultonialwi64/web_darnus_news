<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Grid::make(3)->columnSpan('full')->schema([
                    // Bagian Kiri (Konten Editorial Utama)
                    Section::make('Editorial Content')
                        ->columnSpan(['default' => 3, 'lg' => 2])
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(100)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, $set) {
                                    if ($operation === 'create') {
                                        $baseSlug = Str::slug($state);
                                        $slug = $baseSlug;
                                        $counter = 1;
                                        while (\App\Models\Post::where('slug', $slug)->exists()) {
                                            $slug = $baseSlug . '-' . $counter;
                                            $counter++;
                                        }
                                        $set('slug', $slug);
                                    }
                                }),
                            Hidden::make('slug')
                                ->required()
                                ->dehydrated(),
                            Textarea::make('excerpt')
                                ->label('Deskripsi Singkat (Opsional)')
                                ->maxLength(200)
                                ->helperText('Jika dikosongkan, otomatis diambil dari paragraf pertama konten.'),
                            FileUpload::make('image')
                                ->image()
                                ->directory('posts')
                                ->label('Headline Image (Gambar Utama)')
                                ->helperText('Standard Google News (1200x800). Foto akan otomatis dipotong tengah agar pas dan rapi.')
                                ->imageAspectRatio('3:2')
                                ->automaticallyCropImagesToAspectRatio()
                                ->imageResizeTargetWidth('1200')
                                ->imageResizeTargetHeight('800')
                                ->columnSpanFull(),
                            TextInput::make('image_caption')
                                ->label('Keterangan Foto (Caption)')
                                ->placeholder('Contoh: Suasan di tempat pasca kejadian....')
                                ->helperText('Akan muncul tepat di bawah foto utama berita.')
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->required()
                                ->customBlocks([
                                    \App\Filament\RichEditorBlocks\BacaJugaBlock::class
                                ])
                                ->toolbarButtons([
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'table',
                                    'underline',
                                    'undo',
                                    'customBlocks',
                                ])
                                ->columnSpanFull(),
                        ]),

                    // Bagian Kanan (Metadata & Setting)
                    Section::make('Metadata & Settings')
                        ->columnSpan(1)
                        ->schema([
                            Select::make('region_id')
                                ->label('Daerah / Region')
                                ->relationship('region', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('category_id')->relationship('category', 'name')->required(),
                            \Filament\Forms\Components\ModalTableSelect::make('related_posts')
                                ->label('Berita Terkait (Manual)')
                                ->multiple()
                                ->tableConfiguration(\App\Filament\Resources\Posts\Tables\PostsTable::class)
                                ->getOptionLabelUsing(fn ($value) => \App\Models\Post::find($value)?->title)
                                ->getOptionLabelsUsing(fn ($values) => \App\Models\Post::whereIn('id', (array) $values)->pluck('title', 'id')->all())
                                ->selectAction(fn (\Filament\Actions\Action $action) => $action->modalWidth('4xl'))
                                ->helperText('Kosongkan untuk otomatis mengambil berita satu kategori.'),
                            Select::make('tags')
                                ->multiple()
                                ->relationship('tags', 'name')
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                    Hidden::make('slug')->required()->dehydrated(),
                                ]),
                            TextInput::make('source')
                                ->label('Source / Sumber Berita')
                                ->placeholder('Contoh: Antara, Humas Polri...'),
                            Select::make('author_id')
                                ->relationship('author', 'name')
                                ->label('Author (Penulis)')
                                ->default(fn() => auth()->user()->journalist?->id)
                                ->searchable()
                                ->preload(),
                            Select::make('editor_id')
                                ->relationship('editor', 'name')
                                ->label('Editor')
                                ->default(fn() => auth()->user()->journalist?->id)
                                ->searchable()
                                ->preload(),
                            // --- Status Terbit (Premium Toggle Buttons) ---
                            ToggleButtons::make('publish_status')
                                ->label('Status Terbit')
                                ->options([
                                    'draft'    => 'Draft',
                                    'publish'  => 'Terbit Sekarang',
                                    'schedule' => 'Jadwalkan',
                                ])
                                ->icons([
                                    'draft'    => 'heroicon-m-pencil-square',
                                    'publish'  => 'heroicon-m-check-badge',
                                    'schedule' => 'heroicon-m-clock',
                                ])
                                ->colors([
                                    'draft'    => 'gray',
                                    'publish'  => 'success',
                                    'schedule' => 'info',
                                ])
                                ->default('draft')
                                ->inline()
                                ->live()
                                ->dehydrated(false)
                                ->afterStateHydrated(function ($component, $record) {
                                    if (!$record) { $component->state('draft'); return; }
                                    if (!$record->is_published) {
                                        $component->state('draft');
                                    } elseif ($record->published_at && $record->published_at->isFuture()) {
                                        $component->state('schedule');
                                    } else {
                                        $component->state('publish');
                                    }
                                })
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state === 'draft') {
                                        $set('is_published', false);
                                        $set('published_at', null);
                                    } elseif ($state === 'publish') {
                                        $set('is_published', true);
                                        $set('published_at', null);
                                    } elseif ($state === 'schedule') {
                                        $set('is_published', true);
                                    }
                                }),
                            // Hidden field: is_published dikontrol oleh Radio
                            Hidden::make('is_published')
                                ->default(false)
                                ->dehydrated(true),
                            // Hidden field: published_at dikombinasi dari date+time di bawah
                            Hidden::make('published_at')
                                ->dehydrated(true),
                            // --- PILIH TANGGAL (hanya kalender, klik saja) ---
                            DatePicker::make('schedule_date')
                                ->label('Tanggal Terbit')
                                ->visible(fn ($get) => $get('publish_status') === 'schedule')
                                ->required(fn ($get) => $get('publish_status') === 'schedule')
                                ->native(false)
                                ->minDate(today())
                                ->displayFormat('d F Y')
                                ->placeholder('Pilih tanggal...')
                                ->dehydrated(false)
                                ->live()
                                ->afterStateHydrated(function ($component, $record) {
                                    if ($record?->published_at?->isFuture()) {
                                        $component->state($record->published_at->format('Y-m-d'));
                                    }
                                })
                                ->afterStateUpdated(function ($state, $get, $set) {
                                    $time = $get('schedule_time') ?? '08:00';
                                    if ($state) {
                                        $set('published_at', $state . ' ' . $time . ':00');
                                    }
                                }),
                            // --- PILIH JAM (dropdown preset, tidak perlu ketik) ---
                            TextInput::make('schedule_time')
                                ->label('Jam Terbit')
                                ->placeholder('2300 → 23:00')
                                ->helperText('Ketik 4 angka, titik dua otomatis. Contoh: 0800, 1430, 2100')
                                ->extraInputAttributes(['x-mask' => '99:99'])
                                ->visible(fn ($get) => $get('publish_status') === 'schedule')
                                ->required(fn ($get) => $get('publish_status') === 'schedule')
                                ->default('08:00')
                                ->regex('/^([01]\d|2[0-3]):([0-5]\d)$/')
                                ->validationMessages(['regex' => 'Format jam harus HH:MM, contoh: 08:00 atau 21:30'])
                                ->dehydrated(false)
                                ->live(onBlur: true)
                                ->afterStateHydrated(function ($component, $record) {
                                    if ($record?->published_at?->isFuture()) {
                                        $component->state($record->published_at->format('H:i'));
                                    }
                                })
                                ->afterStateUpdated(function ($state, $get, $set) {
                                    $date = $get('schedule_date');
                                    if ($date && $state && preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $state)) {
                                        $set('published_at', $date . ' ' . $state . ':00');
                                    }
                                }),
                            Toggle::make('is_featured')->label('Pin as Headline')->default(false),
                        ]),
                ])
            ]);
    }
}