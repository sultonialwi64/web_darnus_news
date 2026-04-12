<?php

namespace App\Filament\Resources\Regions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('domain')
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
