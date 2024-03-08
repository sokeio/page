<?php

namespace Sokeio\Page\Livewire;

use Sokeio\Page\Models\Page;
use Sokeio\Components\Table;
use Sokeio\Components\UI;

class PageTable extends Table
{
    protected function getModel()
    {
        return Page::class;
    }
    public function getTitle()
    {
        return __('Page');
    }
    protected function getRoute()
    {
        return 'admin.page';
    }
    protected function getButtons()
    {
        return apply_filters('CMS_PAGE_BUTTONS', [
            UI::buttonCreate(__('Create'))->modalRoute($this->getRoute() . '.add')->modalTitle(__('Create Data'))->modalFullscreen(),
            UI::button(__('Create With Builder'))->Link(function () {
                if (!pageWithBuilder()) {
                    return '#';
                }
                return route('admin.page.create-builder');
            })->When(function () {
                return pageWithBuilder();
            }),
        ]);
    }
    protected function getTableActions()
    {
        return  apply_filters('CMS_PAGE_TABLE_ACTIONS', [
            UI::buttonEdit(__('Edit'))->modalRoute($this->getRoute() . '.edit', function ($row) {
                return [
                    'dataId' => $row->id
                ];
            })->modalTitle(__('Edit Data'))->modalFullscreen(),
            UI::button(__('Edit With Builder'))->Link(function ($item) {
                if (!pageWithBuilder()) {
                    return '#';
                }
                return route('admin.page.edit-builder', ['dataId' => $item->getDataItem()->id]);
            })->When(function () {
                return pageWithBuilder();
            }),
            UI::buttonRemove(__('Remove'))->confirm(__('Do you want to delete this record?'), 'Confirm')->wireClick(function ($item) {
                return 'doRemove(' . $item->getDataItem()->id . ')';
            })
        ]);
    }
    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Title'))->fieldValue(function ($item) {
                return  "<a href='" . $item->getSeoCanonicalUrl() . "' title='{$item->name}' target='_blank'>{$item->name}</a>";
            }),
            UI::text('layout')->label(__('Layout'))->NoSort(),
            UI::text('status')->label(__('Status'))->NoSort(),
            UI::text('created_at')->label(__('Created At')),
            UI::text('updated_at')->label(__('Updated At')),
            // UI::buttonList(UI::forEach($this->langs, [
            //     UI::button(function ($item) {
            //         return sokeioFlags($item->getEachData()->flag, '1x1');
            //     })->modalRoute($this->getRoute() . '.edit', function ($row, $item) {
            //         return  ['dataId' => $row->id, 'lang' => $item->getEachData()->code];
            //     })->modalTitle(__('Edit Data'))->modalFullscreen()->When(function ($item) {
            //         return $item->getEachData()->flag != '';
            //     })->Small()->buttonColor('-icon')
            // ]))->label(__('Languages'))->NoSort()
        ];
    }
}
