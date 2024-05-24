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
                UI::text('title')->label(__('Title'))->required(),
                UI::textarea('description')->label(__('Description')),

                UI::select('status')->label(__('Status'))->dataSource(function () {
                    return [
                        [
                            'id' => 'draft',
                            'title' => __('Draft')
                        ],
                        [
                            'id' => 'published',
                            'title' => __('Published')
                        ]
                    ];
                })->valueDefault('published'),
                // UI::datePicker('published_at')->label(__('Published At')),
                UI::image('image')->label(__('Image')),

                UI::select('layout')->label(__('Layout'))->dataSource(function () {
                    return [
                        [
                            'id' => 'default',
                            'title' => __('Default')
                        ],
                        ...collect(Theme::getLayouts())->duplicates()->where(function ($item) {
                            return $item != 'default';
                        })->map(function ($layout) {
                            return [
                                'id' => $layout,
                                'title' => $layout
                            ];
                        })
                    ];
                }),
                UI::select('view_layout')->label(__('View Layout'))->dataSource(function () {
                    return [
                        [
                            'id' => 'page::page',
                            'title' => __('Page Default')
                        ],
                        [
                            'id' => 'page::page-title',
                            'title' => __('Page With Title')
                        ],
                        [
                            'id' => 'page::page-no-title',
                            'title' => __('Page Without Title')
                        ],
                    ];
                }),
                UI::textarea('custom_js')->label(__('Custom Js')),
                UI::textarea('custom_css')->label(__('Custom CSS')),
                UI::button(__('Save Page'))->wireClick('doSave()')->className('w-100 mb-2'),
            ])
        ]));
    }
    protected function getModel()
    {
        return Page::class;
    }
}
