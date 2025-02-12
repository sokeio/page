<?php

namespace Sokeio\Page\Models;

use Sokeio\Model;

class Slug extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'slug',
        'sluggable_id',
        'sluggable_type'
    ];
    public function sluggable()
    {
        return $this->morphTo();
    }
    // get the sluggable model and cache it
    public static function findBySlug($slug, $type = null, $cached = true)
    {
        if (!$cached) {
            return static::where('slug', $slug)->first();
        }
        return cache()->rememberForever('slug-' . $slug, function () use ($slug, $type) {
            return static::where('slug', $slug)->when($type, function ($query) use ($type) {
                return $query->where('sluggable_type', $type);
            })->first();
        });
    }
    public static function findSluggableBySlug($slug, $type = null, $cached = true)
    {
        $objSlug = static::findBySlug($slug, $type, $cached);
        return $objSlug ? $objSlug->sluggable : null;
    }
}
