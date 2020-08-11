<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'articles';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'content',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'slug',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the category that owns the article.
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    /**
     * The tags that belong to the user.
     */
    public function tags() {
        return $this->belongsToMany('App\Models\Tag', 'article_tag', 'article_id', 'tag_id');
    }

    /**
     * Get the comments for the article.
     */
    public function comments() {
        return $this->hasMany('App\Models\Comments');
    }
}
