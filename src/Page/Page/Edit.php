<?php

namespace Sokeio\Page\Page\Page;

use Carbon\Carbon;
use Sokeio\Page\Models\Page;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\Components\Field\SelectField;
use Sokeio\Page\Enums\PublishedType;
use Sokeio\Theme;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\ContentEditor;
use Sokeio\UI\Field\DatePicker;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\CodeEditor;
use Sokeio\UI\Field\MediaFile;
use Sokeio\UI\Field\Select;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\PageUI;
use Sokeio\UI\Tab\TabControl;
use Sokeio\UI\WithEditUI;

#[AdminPageInfo(title: 'Page', model: Page::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function setupUI()
    {
        return [
            PageUI::make(
                Div::make([
                    Div::make([
                        Input::make('title')->label(__('Title'))->ruleRequired(),
                        Textarea::make('description')->label(__('Description')),
                        TabControl::make()
                            ->iconSize(3)
                            ->tabItem(__('Content'), 'ti ti-file-text', [
                                ContentEditor::make('content')
                            ])
                            ->tabItem(
                                __('Custom'),
                                'ti ti-settings',
                                Div::make([
                                    CodeEditor::make('custom_js')->label(__('Custom JS')),
                                    CodeEditor::make('custom_css')->label(__('Custom CSS'))->language('css'),
                                ])
                            )
                    ])->col9(),
                    Div::make([
                        Select::make()->dataSourceWithEnum(PublishedType::class)->label(__('Published'))->valueDefault(PublishedType::PUBLISHED->value),
                        DatePicker::make('published_at')->label(__('Published At'))->enableTime()
                            ->valueDefault(Carbon::now()->format('Y-m-d H:i:s')),
                        MediaFile::make('image')->label(__('Image')),
                        Select::make('template')->label(__('Template'))->dataSource(Theme::getTemplateOptions())
                            ->valueDefault('none')
                            ->when(function (Select $field) {
                                return $field->checkDataSource();
                            })
                    ])->col3(),
                ])->row()->className('g-2')
            )
                ->prefix('formData')->xxlSize()
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Cancel'))->className('btn btn-warning me-2')->modalClose(),
                        Button::make()->label(__('Save'))->wireClick('saveData')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
