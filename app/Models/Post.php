<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (auth()->check() && !$post->created_by) {
                $post->created_by = auth()->id();
            }
        });
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
public function region() { return $this->belongsTo(Region::class); }
public function category() { return $this->belongsTo(Category::class); }
    public function tags() { return $this->belongsToMany(Tag::class); }
    public function author() { return $this->belongsTo(Journalist::class, 'author_id'); }
    public function editor() { return $this->belongsTo(Journalist::class, 'editor_id'); }

    public function getSummaryAttribute()
    {
        return $this->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($this->content), 160);
    }
}
