<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = ['title', 'content', 'slug', 'category_id', 'user_id'];

    public function getLimitContentAttribute()
    {
        return Str::limit($this->content, 90, '..');
    }

    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withPivot(['is_deleted'])
                        ->wherePivot('is_deleted', false)->as('tag');
    }
    
    public function deletedTags()
    {
        return $this->belongsToMany(Tag::class)->withPivot(['is_deleted'])
                        ->wherePivot('is_deleted', true)->as('tag');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true
            ]
        ];
    }
}
