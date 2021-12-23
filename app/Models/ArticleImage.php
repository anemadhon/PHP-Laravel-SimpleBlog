<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['path', 'article_id'];

    const MAX_IMAGES = 4;

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
