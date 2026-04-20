<?php

namespace App\Filament\Resources\Journalists\Pages;

use App\Filament\Resources\Journalists\JournalistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJournalist extends CreateRecord
{
    protected static string $resource = JournalistResource::class;
}
