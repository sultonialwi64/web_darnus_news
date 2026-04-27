<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduledPostResource\Pages;
use App\Models\Post;
use App\Filament\Resources\Posts\PostResource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class ScheduledPostResource extends PostResource
{
    protected static ?string $slug = 'scheduled-posts';
    protected static string|\UnitEnum|null $navigationGroup = 'Konten Berita';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public static function getNavigationIcon(): \BackedEnum|\Illuminate\Contracts\Support\Htmlable|string|null
    {
        return 'heroicon-o-clock';
    }

    public static function getNavigationLabel(): string
    {
        return 'Berita Terjadwal';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getModel(): string
    {
        return Post::class;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto'),
                TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->visibleFrom('md'),
                TextColumn::make('published_at')
                    ->label('⏰ Jadwal Tayang')
                    ->dateTime('d M Y — H:i')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-clock'),
                TextColumn::make('region.name')
                    ->label('Daerah')
                    ->visibleFrom('lg'),
            ])
            ->defaultSort('published_at', 'asc')  // yang paling dekat jadwalnya di atas
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateHeading('Belum ada berita terjadwal')
            ->emptyStateDescription('Berita yang dijadwalkan akan muncul di sini.');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListScheduledPosts::route('/'),
            'create' => Pages\CreateScheduledPost::route('/create'),
            'edit'   => Pages\EditScheduledPost::route('/{record}/edit'),
        ];
    }
}
