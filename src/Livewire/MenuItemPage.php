<?php

namespace Sokeio\Page\Livewire;

use Sokeio\Page\Models\Page;
use Sokeio\Components\FormMenu;
use Sokeio\Components\UI;
use Sokeio\Menu\MenuItemBuilder;

class MenuItemPage extends FormMenu
{
    public static function renderItem(MenuItemBuilder $item)
    {
        echo  viewScope('sokeio::menu.item.link', ['item' => $item, 'link' => Page::find($item->getValueContentData())?->getSeoCanonicalUrl()])->render();
    }
    public static function getMenuName()
    {
        return __('Page');
    }
    public static function getMenuType()
    {
        return 'MenuItemPage';
    }
    public function SearchPages($text)
    {
        $this->skipRender();
        return Page::query()->where('name', 'like', '%' . $text . '%')->limit(20)->get(['id', 'name']);
    }
    protected function MenuUI()
    {
        return [
            UI::selectWithSearch('data')->label(__('Page'))->required()->searchFn('SearchPages')->dataSource(function () {
                return $this->SearchPages('');
            }),
        ];
    }
}
