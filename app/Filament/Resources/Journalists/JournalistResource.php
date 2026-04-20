<?php

namespace App\Filament\Resources\Journalists;

use App\Filament\Resources\Journalists\Pages\CreateJournalist;
use App\Filament\Resources\Journalists\Pages\EditJournalist;
use App\Filament\Resources\Journalists\Pages\ListJournalists;
use App\Filament\Resources\Journalists\Schemas\JournalistForm;
use App\Filament\Resources\Journalists\Tables\JournalistsTable;
use App\Models\Journalist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JournalistResource extends Resource
{
    protected static ?string $model = Journalist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $navigationLabel = 'Redaktur';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return JournalistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalistsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJournalists::route('/'),
            'create' => CreateJournalist::route('/create'),
            'edit' => EditJournalist::route('/{record}/edit'),
        ];
    }
}
