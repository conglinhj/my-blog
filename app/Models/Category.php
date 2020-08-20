<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'description',
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
     * Get the parent category of this
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    /**
     * Get the child categories of this
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function childCategories() {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    /**
     * Get the articles of the category
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function articles() {
        return $this->hasMany('App\Models\Article', 'category_id');
    }
}
