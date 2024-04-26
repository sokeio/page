<?php

namespace Sokeio\Page\Livewire;

use Sokeio\Page\Models\Page;
use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Facades\Locale;

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
    protected function getModalSize($isNew = true, $row = null)
    {
        return UI::MODAL_FULLSCREEN;
    }
    protected function getButtons()
    {
        return applyFilters('CMS_PAGE_BUTTONS', [
            UI::button(__(''))
                ->icon('<i class="ti ti-drag-drop fs-2 me-1"></i>')
                ->title(__('Builder'))
                ->Link(function () {
                    if (!pageWithBuilder()) {
                        return '#';
                    }
                    return route('admin.page.create-builder');
                })->when(function () {
                    return pageWithBuilder();
                }),
            ...parent::getButtons(),
        ]);
    }
    protected function getTableActions()
    {
        return  applyFilters('CMS_PAGE_TABLE_ACTIONS', [
            UI::button(__(''))
                ->icon('<i class="ti ti-drag-drop fs-2 me-1"></i>')
                ->title(__('Builder'))
                ->Link(function ($item) {
                    return route('admin.page.edit-builder', ['dataId' => $item->getDataItem()->id]);
                })->when(function () {
                    return pageWithBuilder();
                }),
            UI::buttonRemove(__('Remove'))
                ->confirm(__('Do you want to delete this record?'), 'Confirm')
                ->wireClick(function ($item) {
                    return 'doRemove(' . $item->getDataItem()->id . ')';
                })
        ]);
    }
    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Title'))->setLink(),
            UI::text('layout')->label(__('Layout'))->NoSort(),
            UI::datePicker('published_at')->label(__('Published At'))->NoSort(),
            UI::text('status')->label(__('Status'))->NoSort(),
            UI::text('created_at')->label(__('Created At')),
            UI::text('updated_at')->label(__('Updated At')),
            UI::text("")->label(__('Languages'))->noSave()->noSort()
                ->fieldValue(function ($row) {
                    sokeioFieldLocateRender($row, 'admin.page.edit', 'Edit Page');
                }),

        ];
    }
}
