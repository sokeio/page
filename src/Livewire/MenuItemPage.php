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
        echo viewScope('sokeio::menu.item.link', [
            'item' => $item,
            'link' => Page::find($item->getValueContentData())?->getSeoCanonicalUrl()
        ])->render();
    }
    public static function getMenuName()
    {
        return __('Page');
    }
    public static function getMenuType()
    {
        return 'MenuItemPage';
    }
    protected function MenuUI()
    {
        return [
            UI::selectWithSearch('data')
                ->label(__('Page'))->required()->querySearchFn(function ($component, $text, $currentId = null) {
                    $component->skipRender();

                    $rs = Page::query()
                        ->when($text != "", function ($query) use ($text) {
                            $query->where('name', 'like', '%' . $text . '%');
                        })
                        ->limit(20)->get(['id', 'name']);
                    if ($currentId && $text == '') {
                        $currentPage = Page::find($currentId);
                        if ($currentPage) {
                            return [
                                [
                                    'id' => $currentPage->id,
                                    'name' => $currentPage->name
                                ],
                                ...$rs->toArray(),
                            ];
                        }
                    }
                    return $rs;
                }),
        ];
    }
}
