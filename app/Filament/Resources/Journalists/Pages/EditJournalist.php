<?php

namespace App\Filament\Resources\Journalists\Pages;

use App\Filament\Resources\Journalists\JournalistResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJournalist extends EditRecord
{
    protected static string $resource = JournalistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
