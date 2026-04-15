<?php

namespace App\Filament\Resources\Posts\Schemas;

class PostForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('region_id')->relationship('region', 'name')->required(),
                \Filament\Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),
                \Filament\Forms\Components\TextInput::make('title')->required(),
                \Filament\Forms\Components\TextInput::make('slug')->required(),
                \Filament\Forms\Components\FileUpload::make('image')->image()->directory('posts'),
                \Filament\Forms\Components\Toggle::make('is_published')->default(true),
                \Filament\Forms\Components\Toggle::make('is_featured')->label('Pin to Headline')->default(false),
                \Filament\Forms\Components\RichEditor::make('content')->columnSpanFull(),
            ]);
    }
}