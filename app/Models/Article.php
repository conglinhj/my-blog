<?php

namespace App\Models;

use Carbon\Carbon;
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
        'published_at' => 'datetime',
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
        'author_id',
        'category_id',
        'is_published'
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
     * Auto generate slug with title
     * @param string $value
     */
    public function setTitleAttribute($value) {
        $this->attributes['title'] = $value;
        $suffix = Carbon::now()->format('YmdHis');
        $this->attributes['slug'] = preg_replace('/\s+/', '-', $value) . '_' . $suffix;
    }

    /**
     * save the first publication date only
     * @param boolean $value
     */
    public function setIsPublishedAttribute($value) {
        $this->attributes['is_published'] = $value;
        if ($this->is_published && !$this->published_at) {
            $this->attributes['published_at'] = Carbon::now();
        }
    }

    /**
     * Get the category that owns the article.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    /**
     * The tags that belong to the article.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags() {
        return $this->belongsToMany('App\Models\Tag', 'article_tag', 'article_id', 'tag_id');
    }

    /**
     * Get the comments for the article.
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * Get all published articles
     * @return mixed
     */
    public static function getPublished() {
        return self::where([['is_published', true]])->get();
    }
}
