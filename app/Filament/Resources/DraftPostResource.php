<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DraftPostResource\Pages;
use App\Models\Post;
use App\Filament\Resources\Posts\PostResource;
use Illuminate\Database\Eloquent\Builder;

class DraftPostResource extends PostResource
{
    protected static ?string $slug = 'draft-posts';
    
    public static function getNavigationIcon(): \BackedEnum|\Illuminate\Contracts\Support\Htmlable|string|null
    {
        return 'heroicon-o-document-text';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Draft Berita';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getModel(): string
    {
        return Post::class;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_published', false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDraftPosts::route('/'),
            'create' => Pages\CreateDraftPost::route('/create'),
            'edit' => Pages\EditDraftPost::route('/{record}/edit'),
        ];
    }
}
