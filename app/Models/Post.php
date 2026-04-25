<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'related_posts' => 'array',
    ];

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

    public function getRenderedContentAttribute()
    {
        $html = $this->content;

        if (class_exists(\Filament\Forms\Components\RichEditor\RichContentRenderer::class)) {
            $html = \Filament\Forms\Components\RichEditor\RichContentRenderer::make($this->content)
                ->customBlocks([
                    \App\Filament\RichEditorBlocks\BacaJugaBlock::class
                ])
                ->toHtml();
        }

        // --- AUTO-TAG HIGHLIGHTING ---
        // Mencari kata yang cocok dengan tag dan memberikan link otomatis
        $tags = $this->tags;
        foreach ($tags as $tag) {
            $pattern = '/\b(' . preg_quote($tag->name, '/') . ')\b(?![^<]*>|[^<>]*<\/a>)/i';
            $url = route('tag.show', $tag->slug);
            
            $html = preg_replace(
                $pattern, 
                '<a href="' . $url . '" class="font-bold text-[#f97316] hover:text-[#ea580c] transition-colors">$1</a>', 
                $html
            );
        }
        
        return $html;
    }
}
