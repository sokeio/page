<?php

namespace Sokeio\Page\Page\Setting;

use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\Page\Models\Page;
use Sokeio\Theme;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\ContentEditor;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaFile;
use Sokeio\UI\Field\Select;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\PageUI;
use Sokeio\UI\SettingUI;
use Sokeio\UI\WithSettingUI;

#[AdminPageInfo(
    title: 'Homepage',
    menu: true,
    menuTitle: 'Homepage',
    menuIcon: 'ti ti-home',
    menuTargetSort: 99999,
    icon: 'ti ti-home',
    sort: -100,
)]
class HomePage extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_HOMEPAGE_SETTING_PAGE";
    use WithSettingUI;
    private function settingOverview()
    {
        return SettingUI::make([
            Input::make('SOKEIO_SITE_TITLE')->col4()
                ->label('Site Title')
                ->ruleRequired('Please enter Site title')
                ->placeholder('Site Title')
                ->valueDefault('Sokeio Technology'),
            MediaFile::make('SOKEIO_SITE_LOGO')->col4()
                ->label('Site Logo'),
            ContentEditor::make('SOKEIO_SITE_DESCRIPTION')
                ->col12()
                ->label('Site Description')
                ->placeholder('Site Description'),
            Select::make('SOKEIO_SITE_HOMEPAGE')->col4()->label('Homepage')->dataSourceWithModel(Page::class, 'title'),
        ])
            ->bodyRow()
            ->title('Site Setting')
            ->subtitle('')
            ->column(self::COLUMN_GROUP)
            ->prefix('formData.overview')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                $this->settingOverview(),
            ])->childWithKey(self::KEY_UI)->row()->rightUI([
                Button::make()
                    ->className('btn btn-primary')
                    ->label('Save Setting')
                    ->icon('ti ti-settings')
                    ->wireClick('saveData')
            ])
                ->icon('ti ti-login')

        ];
    }
}
