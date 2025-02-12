<?php

namespace Sokeio\Page;

use Sokeio\Page\Models\Slug;

trait WithSluggable
{
    public function getRouteKey()
    {
        return 'slug';
    }
    public function getRouteName()
    {
        return '';
    }
    public function getUrlAttribute()
    {
        if (!$this->sluggable) {
            $this->checkSlug();
        }
        return route($this->getRouteName(), [
            'slug' => $this->sluggable->slug
        ]);
    }
    public static function bootWithSluggable()
    {
        static::saved(function ($model) {
            $model->checkSlug();
        });
    }
    public function sluggable()
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }
    public function getTitle()
    {
        return $this->title;
    }
    private function checkSlug()
    {
        if (!$this->sluggable) {
            $slug = str($this->getTitle())->slug();
            //Check Slug is not Exists then create else make it unique
            if (Slug::query()->where('slug', $slug)->exists()) {
                $slug = $slug . '-' . time();
            }
            $this->sluggable()->create(['slug' => $slug]);
            unset($this->sluggable);
        }
    }
    public function resolveRouteBinding($value, $field = null)
    {
        return  $this->whereHas('sluggable', function ($query) use ($value, $field) {
            $query->where($field ?? 'slug', $value);
        })->first();
    }
    public function resolveSoftDeletableRouteBinding($value, $field = null)
    {
        return  $this->whereHas('sluggable', function ($query) use ($value, $field) {
            $query->where($field ?? 'slug', $value);
        })->withTrashed()->first();
    }
}
