<?php

namespace Sokeio\Page\Providers;

use Illuminate\Support\ServiceProvider;
use Sokeio\Content\Models\Menu;
use Sokeio\Menu\MenuItem;
use Sokeio\Menu\MenuManager;
use Sokeio\Theme;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // foreach (Theme::getLocationOptions('menu') as $location) {
        //     $this->themeLocation(trim(data_get($location, 'value')));
        // }
        $this->themeLocation('menu_main');
    }
    private function themeLocation($location)
    {
        Theme::location($location, function () use ($location) {
            echo $this->getMenuByLocation($location)->render();
        });
    }
    public function getMenuByLocation($location)
    {
        $menuManager = MenuManager::init();
        $menu =  Menu::query()->whereJsonContains('locations', $location)->with('items')->first();
        if ($menu) {
            foreach ($menu->items as $item) {
                $target = $item->parent_id > 0 ? 'item_' . $item->parent_id : '';
                $menuManager->register(
                    MenuItem::make('item_' . $item->id, $item->title, $item->url, $item->icon, $item->order,  $target)
                );
            }
        }
        return $menuManager;
    }
}
