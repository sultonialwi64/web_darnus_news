<?php

namespace App\Filament\Resources\Journalists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JournalistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
            ]);
    }
}
