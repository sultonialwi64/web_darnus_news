<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\EditAction;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('title')->searchable(),
                TextColumn::make('category.name')->label('Kategori'),
                TextColumn::make('region.name')->label('Daerah'),
                ToggleColumn::make('is_featured')->label('Headline'),
                ToggleColumn::make('is_published')->label('Published'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                // Ini adalah class yang BENAR untuk Action di dalam Tabel (Filament v3)
                EditAction::make(),
            ]);
    }
}