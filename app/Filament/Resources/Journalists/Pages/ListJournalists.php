<?php

namespace App\Filament\Resources\Journalists\Pages;

use App\Filament\Resources\Journalists\JournalistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJournalists extends ListRecords
{
    protected static string $resource = JournalistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
