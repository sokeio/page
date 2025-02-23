<?php

namespace Sokeio\Page;

use Sokeio\Page\Enums\PublishedType;
use Sokeio\Page\Models\Slug;
use Sokeio\Theme;

class SlugHelper
{
    private static $hooks = [];
    private static $hooksTypes = [];
    public static function hook($callback, $type = null)
    {
        if ($type) {
            self::$hooksTypes[$type][] = $callback;
        } else {
            self::$hooks[] = $callback;
        }
    }
    private static function applyHooks($slug, $data, $type = null)
    {
        foreach (self::$hooksTypes[$type] ?? [] as $hook) {
            $data = $hook($slug, $data, $type);
        }
        foreach (self::$hooks as $hook) {
            $data = $hook($slug, $data, $type);
        }
        return $data;
    }

    // get the sluggable model and cache it
    public static function findBySlug($slug, $type = null, $cached = true)
    {
        if (!$cached) {
            return Slug::where('slug', $slug)->first();
        }
        return cache()->rememberForever('slug-' . $slug, function () use ($slug, $type) {
            return Slug::where('slug', $slug)->when($type, function ($query) use ($type) {
                return $query->where('sluggable_type', $type);
            })->first();
        });
    }
    public static function findSluggableBySlug($slug, $type = null, $cached = true)
    {
        $objSlug = static::findBySlug($slug, $type, $cached);
        return $objSlug ? $objSlug->sluggable : null;
    }
    public static function render($slug, $view, $type, $item = null)
    {
        if ($slug) {
            $item =  static::findSluggableBySlug($slug, $type);
            Theme::title($item?->title);
            Theme::description($item?->description);
        }
        if (!$item) {
            return abort(404);
        }
        if ($item->published_type  === PublishedType::DRAFT) {
            return abort(404);
        }
        if ($item->published_type === PublishedType::SCHEDULED && !$item->published_at->isFuture()) {
            return abort(404);
        }
        return Theme::view(
            $view,
            self::applyHooks($slug, ['item' => $item], $type),
            [],
            false,
            $item->template
        );
    }
}
