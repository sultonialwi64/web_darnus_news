<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Kosongkan jika tidak ingin mengubah password (saat edit).'),
                Select::make('role')
                    ->label('Peran / Role')
                    ->options([
                        'admin'      => 'Admin (Akses Penuh)',
                        'journalist' => 'Jurnalis / Reporter',
                    ])
                    ->default('journalist')
                    ->required(),
            ]);
    }
}
