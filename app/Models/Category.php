<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
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
        'level',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * @param int $value
     */
    protected function setParentIdAttribute(int $value) {
        $this->attributes['parent_id'] = $value;
        $this->attributes['level'] = $value ? $value + 1 : 0;
    }

    /**
     * Get the parent category of this
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    /**
     * Get the child categories of this
     * @return hasMany
     */
    public function childrenCategories(): hasMany
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    /**
     * Get the articles of the category
     * @return hasMany
     */
    public function articles(): hasMany
    {
        return $this->hasMany('App\Models\Article', 'category_id');
    }
}
