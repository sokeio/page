<?php

namespace Sokeio\Page;

use Sokeio\Builder\FormBuilder;
use Sokeio\Page\Models\Page;
use Sokeio\Components\UI;
use Sokeio\Facades\Theme;

class PageBuilder extends FormBuilder
{
    protected function getTitle()
    {
        return __('Page');
    }
    protected function footerUI()
    {
        return [];
    }

    protected function getPageList()
    {
        return route('admin.page');
    }
    protected function getLinkView()
    {
        return $this->data->slug ? route('page.slug', ['page' => $this->data->slug]) : '';
    }
    protected function formUI()
    {
        return UI::prex('data', UI::row([
            UI::column12([
                UI::hidden('content')->valueDefault('')->required()->label(__('Content')),
                UI::hidden('author_id')->valueDefault(function () {
                    return auth()->user()->id;
                }),
                UI::Div(UI::error('content')),
                UI::text('name')->label(__('Title'))->required(),
                UI::text('slug')->label(__('Slug')),
                UI::textarea('description')->label(__('Description')),

                UI::select('status')->label(__('Status'))->dataSource(function () {
                    return [
                        [
                            'id' => 'draft',
                            'name' => __('Draft')
                        ],
                        [
                            'id' => 'published',
                            'name' => __('Published')
                        ]
                    ];
                })->valueDefault('published'),
                // UI::datePicker('published_at')->label(__('Published At')),
                UI::image('image')->label(__('Image')),

                UI::select('layout')->label(__('Layout'))->dataSource(function () {
                    return [
                        [
                            'id' => 'default',
                            'name' => __('Default')
                        ],
                        ...collect(Theme::getLayouts())->duplicates()->where(function ($item) {
                            return $item != 'default';
                        })->map(function ($layout) {
                            return [
                                'id' => $layout,
                                'name' => $layout
                            ];
                        })
                    ];
                }),
                UI::select('view_layout')->label(__('View Layout'))->dataSource(function () {
                    return [
                        [
                            'id' => 'page::page',
                            'name' => __('Page Default')
                        ],
                        [
                            'id' => 'page::page-title',
                            'name' => __('Page With Title')
                        ],
                        [
                            'id' => 'page::page-no-title',
                            'name' => __('Page Without Title')
                        ],
                    ];
                }),
                UI::checkBox('is_container')->label(__('With Container')),
                UI::textarea('custom_js')->label(__('Custom Js')),
                UI::textarea('custom_css')->label(__('Custom CSS')),
                UI::button(__('Save article'))->wireClick('doSave()')->className('w-100 mb-2'),
            ])
        ]));
    }
    protected function getModel()
    {
        return Page::class;
    }
}
