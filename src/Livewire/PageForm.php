<?php

namespace Sokeio\Page\Livewire;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Facades\Theme;
use Sokeio\Page\Models\Page;

class PageForm extends Form
{

    public function getTitle()
    {
        return __('Page');
    }
    public function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    public function getButtons()
    {
        return [];
    }
    public function getModel()
    {
        return Page::class;
    }
    protected function footerUI()
    {
        return null;
    }
    public function formUI()
    {
        return UI::container([
            UI::prex(
                'data',
                [
                    UI::hidden('author_id')->valueDefault(auth()->user()->id),
                    UI::row([
                        UI::column8([
                            UI::text('title')->label(__('Title'))->required(),
                            UI::tinymce('content')->label(__('Content'))->required(),
                            UI::textarea('description')->label(__('Description')),
                            UI::textarea('custom_js')->label(__('Custom Js')),
                            UI::textarea('custom_css')->label(__('Custom CSS')),
                        ]),
                        UI::column4([
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
                            UI::datePicker('published_at')->label(__('Published At'))->fieldOption([
                                'enableTime' => true
                            ]),
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
                            UI::button(__('Save article'))->wireClick('doSave()')->className('w-100 mb-2'),
                        ]),
                    ]),
                ]
            )
        ])
            ->className('p-3');
    }
}
