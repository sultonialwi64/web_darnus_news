<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->description(fn($record) => $record->is_published ? null : '📝 DRAFT')
                    ->color(fn($record) => $record->is_published ? null : 'warning'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->toggleable(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->toggleable(),
                TextColumn::make('region.name')
                    ->label('Daerah')
                    ->toggleable(),
                ToggleColumn::make('is_featured')->label('Headline'),
                ToggleColumn::make('is_published')->label('Published'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('drafts')
                    ->label('Hanya Draft')
                    ->query(fn (Builder $query) => $query->where('is_published', false)),
                Filter::make('published')
                    ->label('Hanya Terbit')
                    ->query(fn (Builder $query) => $query->where('is_published', true)),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}