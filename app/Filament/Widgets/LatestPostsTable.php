<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPostsTable extends BaseWidget
{
    protected static ?int $sort = 2; // Biar ditaruh di bawah statistik

    protected int | string | array $columnSpan = 'full'; // Tabel melebar penuh

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $isJournalist = $user->role === 'journalist';
        $journalistId = $user->journalist?->id;

        $query = Post::query();
        if ($isJournalist) {
            $query->where('author_id', $journalistId);
        }

        return $table
            ->query($query->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make('view')
                    ->label('Lihat')
                    ->url(fn (Post $record): string => \App\Filament\Resources\Posts\PostResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
