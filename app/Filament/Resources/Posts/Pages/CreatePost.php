<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Cegah Error 500 (Duplicate Slug): 
        // Jika AutoSave sudah diam-diam membuat record ini di background,
        // kita ambil record tersebut dan lakukan UPDATE saja, alih-alih INSERT baru.
        if (isset($data['slug'])) {
            $existingPost = static::getModel()::where('slug', $data['slug'])->first();
            
            if ($existingPost) {
                // Update record hasil auto-save dengan data final dari form
                $existingPost->update($data);
                return $existingPost;
            }
        }

        // Jika AutoSave belum sempat berjalan, buat record baru seperti biasa
        return static::getModel()::create($data);
    }
}
