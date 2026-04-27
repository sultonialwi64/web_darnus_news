<?php

namespace App\Filament\RichEditorBlocks;

use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\TableSelect;


class BacaJugaBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'baca-juga';
    }

    public static function getLabel(): string
    {
        return 'Baca Juga';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->form([
                TableSelect::make('post_id')
                    ->label('Pilih Berita')
                    ->tableConfiguration(PostsTable::class)
                    ->required()
            ])
            ->modalWidth('4xl')
            ->modalHeading('Baca Juga')
            ->modalSubmitActionLabel('Sisipkan');
    }

    public static function getPreviewLabel(array $config): string
    {
        if (isset($config['post_id'])) {
            $post = Post::find($config['post_id']);
            if ($post) {
                return 'Baca Juga: ' . $post->title;
            }
        }
        return 'Baca Juga (Tidak Ada Berita Dipilih)';
    }

    public static function toHtml(array $config, array $data): ?string
    {
        if (!isset($config['post_id'])) return null;
        $post = Post::find($config['post_id']);
        if (!$post) return null;
        return "<p><strong>Baca Juga: </strong><a href=\"/news/{$post->slug}\">{$post->title}</a></p>";
    }

    public static function toPreviewHtml(array $config): ?string
    {
        if (!isset($config['post_id'])) return null;
        $post = Post::find($config['post_id']);
        if (!$post) return null;
        return "<div style=\"padding:10px;border-left:3px solid #3b82f6;background:#eff6ff;margin-bottom:20px\">
            <strong>Baca Juga: </strong><a href=\"#\" style=\"color:#2563eb;text-decoration:underline\">{$post->title}</a>
        </div>";
    }
}
