<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($article) {
            // Str::uuid()->toString()
            $article->slug = Str::slug($article->title);
        });
        static::updating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }
}
