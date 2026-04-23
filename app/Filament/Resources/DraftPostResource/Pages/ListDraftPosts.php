<?php

namespace App\Filament\Resources\DraftPostResource\Pages;

use App\Filament\Resources\DraftPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDraftPosts extends ListRecords
{
    protected static string $resource = DraftPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
