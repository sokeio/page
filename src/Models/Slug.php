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
}
