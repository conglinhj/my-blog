<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'author_id',
        'article_id',
        'content',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the author that owns the comment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    /**
     * Get the article that owns the comment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article() {
        return $this->belongsTo('App\Models\Article', 'article_id');
    }

    /**
     * Get the parent comment that owns the comment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentComment() {
        return $this->belongsTo('App\Models\Comment', 'parent_id');
    }

    /**
     * Get the child comments
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function childComments() {
        return $this->hasMany('App\Models\Comment', 'parent_id');
    }
}
