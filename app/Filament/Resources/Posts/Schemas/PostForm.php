<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
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
                                ->helperText('Wajib Landscape (mendatar). Disarankan ukuran minimal 1280 × 720 px (16:9) agar kepala tidak terpotong.')
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
                                        ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
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
                            Toggle::make('is_published')
                                ->label('Terbit Sekarang')
                                ->helperText('Matikan untuk menyimpan sebagai Draft.')
                                ->default(false), // Default: simpan sebagai DRAFT
                            Toggle::make('is_featured')->label('Pin as Headline')->default(false),
                        ]),
                ])
            ]);
    }
}