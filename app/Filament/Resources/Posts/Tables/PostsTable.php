<?php

namespace App\Filament\Resources\Posts\Tables;

class PostsTable
{
    public static function configure(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image'),
                \Filament\Tables\Columns\TextColumn::make('title')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                \Filament\Tables\Columns\TextColumn::make('region.name')->label('Daerah'),
                \Filament\Tables\Columns\ToggleColumn::make('is_published')->label('Published'),
            ])
            ->recordActions([
                // Kita tulis alamat lengkapnya di sini biar PHP gak bingung
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}