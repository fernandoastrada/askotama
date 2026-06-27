<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'title', 
    'slug', 
    'meta_description', 
    'content', 
    'image', 
    'category_blogs'
    ];

    public function category() {
    return $this->belongsTo(Category_blog::class);
    }
}