<?php

namespace Sokeio\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Sokeio\Comment\Concerns\Actionable;
use Sokeio\Comment\Concerns\Commentable;
use Sokeio\Comment\Concerns\Rateable;
use Sokeio\Comment\Concerns\Viewable;
use Sokeio\Concerns\WithModelAssets;
use Sokeio\Concerns\WithSlug;
use Sokeio\Seo\HasSEO;

class Page extends Model
{
    use WithSlug, HasSEO, Commentable, Actionable, Rateable, Viewable;
    use WithModelAssets;
    public function isHomePage()
    {
        return $this->id == setting('PLATFORM_HOMEPAGE');
    }
    public function getSeoCanonicalUrl()
    {
        if ($this->isHomePage()) {
            return url('/');
        }
        return route('page.slug', ['page' => $this->slug]);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'image',
        'view_layout',
        'is_container',
        'status',
        'author_id',
        'published_at',
        'lock_password',
        'app_before',
        'app_after',
        'layout',
        'data',
        'js',
        'css',
        'custom_js',
        'custom_css',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}
