<?php

namespace Sokeio\Page\Models;

use Sokeio\Model;
use Sokeio\Page\Enums\PublishedType;
use Sokeio\Page\WithSluggable;

class Page extends Model
{
    use WithSluggable;
    public function getRouteName()
    {
        return 'site.page';
    }
    /**
     *
     * @var string[]
     */
    protected $fillable = [
        'main_id',
        'locale',
        'title',
        'description',
        'content',
        'image',
        'status',
        'published_at',
        'published_type',
        'password',
        'template',
        'data',
        'data_js',
        'data_css',
        'custom_js',
        'custom_css',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'published_type' => PublishedType::class
    ];
    protected $hidden = [
        'main_id',
        'locale',
        'password',
        'template',
        'data',
        'data_js',
        'data_css',
        'custom_js',
        'custom_css',
        'content',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
