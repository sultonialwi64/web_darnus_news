<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journalist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authoredPosts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function editedPosts()
    {
        return $this->hasMany(Post::class, 'editor_id');
    }
}
