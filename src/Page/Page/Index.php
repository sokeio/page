<?php

namespace Sokeio\Page\Page\Page;

use Sokeio\Page\Models\Page;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\DatePicker;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\PageUI;
use Sokeio\UI\Table\Column;
use Sokeio\UI\Table\Table;
use Sokeio\UI\WithTableEditlineUI;

#[AdminPageInfo(
    title: 'Page',
    menu: true,
    menuTitle: 'Pages',
    model: Page::class,
    sort: 1
)]
class Index extends \Sokeio\Page
{
    use WithTableEditlineUI;
    protected function setupUI()
    {
        return [
            PageUI::make(
                [
                    Table::make()
                        ->columnGroup('published', 'Published')
                        ->column(Column::make('title')->enableLink())
                        ->column(Column::make('description')->editUI(Textarea::make('description')->ruleRequired()))
                        ->column(Column::make('published_type')->columnGroup('published')->editUI(SwitchField::make('published')))
                        ->column(Column::make('published_at')->columnGroup('published')->editUI(DatePicker::make('published_at')))
                        ->column('template')
                        ->query($this->getQuery())
                        ->enableIndex()
                        ->enableCheckbox()
                        ->searchbox(['title', 'description', 'content'])
                        ->columnAction([
                            Button::make()->label(__('Edit'))->className('btn btn-success btn-sm ')
                                ->modal(function (Button $button) {
                                    return route($this->getRouteName('edit'), [
                                        'dataId' =>
                                        $button->getParams('row')->id
                                    ]);
                                }, 'Edit ' . $this->getPageConfig()->getTitle(), 'xl', 'ti ti-pencil'),
                            Button::make()->label(__('Delete'))
                                ->wireClick(function ($params) {
                                    ($this->getModel())::find($params)->delete();
                                }, 'table_delete', function (Button $button) {
                                    return $button->getParams('row')->id;
                                })->className('btn btn-danger ms-1 btn-sm')
                                ->confirm(__('Are you sure?')),

                        ])->rightUI([
                            Button::make()
                                ->label(__('Add ' . $this->getPageConfig()->getTitle()))
                                ->icon('ti ti-plus')
                                ->modalRoute(
                                    $this->getRouteName('edit'),
                                    __('Add ' . $this->getPageConfig()->getTitle()),
                                    'xl',
                                    'ti ti-plus'
                                )

                        ])
                ]
            )

        ];
    }
}
