<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Inject script Auto-Save ke halaman Edit.
     * Script ini akan berjalan di browser dan secara diam-diam mengirim
     * data ke server setiap 30 detik ATAU saat editor menutup tab.
     */
    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        return 'Edit Berita';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
