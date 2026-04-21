<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isJournalist = $user->role === 'journalist';
        $journalistId = $user->journalist?->id;

        $postsQuery = Post::query();
        if ($isJournalist) {
            $postsQuery->where('author_id', $journalistId);
        }

        return [
            Stat::make($isJournalist ? 'Berita Saya' : 'Total Berita', $postsQuery->count())
                ->description($isJournalist ? 'Jumlah artikel yang Anda tulis' : 'Jumlah seluruh artikel berita')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'), // Amber
            Stat::make('Berita Headline', (clone $postsQuery)->where('is_featured', true)->count())
                ->description($isJournalist ? 'Berita Anda yang jadi Headline' : 'Berita utama di halaman depan')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'), 
            Stat::make('Total Rubrik', Category::count())
                ->description('Kategori berita aktif')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'), 
        ];
    }
}
