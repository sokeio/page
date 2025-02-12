<?php

namespace Sokeio\Page;

use Illuminate\Support\ServiceProvider;
use Sokeio\Page\Providers\MenuServiceProvider;
use Sokeio\ServicePackage;
use Sokeio\Core\Concerns\WithServiceProvider;
use Sokeio\Enums\AlertType;
use Sokeio\Page\Enums\UIKey;
use Sokeio\Page\Models\Page;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaIcon;
use Sokeio\UI\Field\Select;
use Sokeio\UI\SoUI;

class PageServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('sokeio-page')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function packageRegistered()
    {
        $this->app->register(MenuServiceProvider::class);
        // packageRegistered
        // Menu Link
        SoUI::registerUI(
            [
                Input::make('name')->label('Name'),
                MediaIcon::make('icon')->label('Icon'),
                Input::make('link')->label('Link'),
                Button::make()->boot(function (Button $button) {
                    if ($button->getValueByKey('id')) {
                        $button->label('Update')
                            ->icon('ti ti-check')
                            ->className('btn btn-success p-2 mt-1');
                    } else {
                        $button->label(__('Add to menu'))
                            ->icon('ti ti-plus')
                            ->className('btn btn-primary p-2 mt-1');
                    }
                })
                    ->wireClick(function (Button $button) {
                        $button->resetErrorBag();
                        $fail = false;
                        if (!($button->getValueByKey('name'))) {
                            $fail = true;
                            $button->addError('name', 'Name is required');
                        }
                        if (!($button->getValueByKey('link'))) {
                            $fail = true;
                            $button->addError('link', 'Link is required');
                        }
                        // if (!($button->getValueByKey('icon'))) {
                        //     $fail = true;
                        //     $button->addError('icon', 'Icon is required');
                        // }
                        if ($fail) {
                            return;
                        }
                        $id = $button->getValueByKey('id');
                        $button->getWire()->updateItemMenu(
                            $id,
                            function ($menuItem) use ($button) {
                                $menuItem->name = $button->getValueByKey('name');
                                $menuItem->link = $button->getValueByKey('link');
                                $menuItem->icon = $button->getValueByKey('icon');
                                $menuItem->type = 'link';
                            }
                        );
                        $button->wireAlert(
                            $button->getValueByKey('name') . ' has been saved!',
                            'Menu',
                            AlertType::SUCCESS
                        );
                        if (!$id) {
                            $button->changeValue('name', '');
                            $button->changeValue('link', '');
                            $button->changeValue('icon', '');
                        }
                    })
                    ->when(function (Button $button) {
                        return $button->getWire()->menuId;
                    }),
                Button::make()->label('Remove')
                    ->icon('ti ti-trash')
                    ->className('btn btn-danger p-2 ms-2 mt-1')
                    ->confirm('Are you really want to remove?')
                    ->wireClick(function (Button $button) {
                        $id = $button->getValueByKey('id');
                        $button->getWire()->removeItemMenu($id);
                    })->when(function (Button $button) {
                        return $button->getValueByKey('id');
                    })
            ],
            UIKey::MENU_ITEM_TYPE->value . 'link',
            UIKey::MENU_ADD_ITEM->value,
            fn(Card $card) => $card->title('Custom Link')->className('mb-2')
                ->boot(function (Card $card) {
                    $card->groupField(null);
                    if (!$card->getValueByKey('id')) {
                        $card->groupField('links');
                    }
                })
        );

        //
        SoUI::registerUI(
            [
                Input::make('name')->label('Name'),
                MediaIcon::make('icon')->label('Icon'),
                Select::make('menuable_id')->remoteActionWithModel(Page::class, 'title')->label('Choose Page'),
                Button::make()->boot(function (Button $button) {
                    if ($button->getValueByKey('id')) {
                        $button->label('Update')
                            ->icon('ti ti-check')
                            ->className('btn btn-success p-2 mt-1');
                    } else {
                        $button->label(__('Add to menu'))
                            ->icon('ti ti-plus')
                            ->className('btn btn-primary p-2 mt-1');
                    }
                })
                    ->wireClick(function (Button $button) {
                        $button->resetErrorBag();
                        $fail = false;
                        // if (!($button->getValueByKey('name'))) {
                        //     $fail = true;
                        //     $button->addError('name', 'Name is required');
                        // }
                        if (!($button->getValueByKey('menuable_id'))) {
                            $fail = true;
                            $button->addError('menuable_id', 'page is required');
                        }
                        // if (!($button->getValueByKey('icon'))) {
                        //     $fail = true;
                        //     $button->addError('icon', 'Icon is required');
                        // }
                        if ($fail) {
                            return;
                        }
                        $id = $button->getValueByKey('id');
                        $button->getWire()->updateItemMenu(
                            $id,
                            function ($menuItem) use ($button) {
                                $menuItem->name = $button->getValueByKey('name');
                                $menuItem->menuable_type = Page::class;
                                $menuItem->menuable_id = $button->getValueByKey('menuable_id');
                                $menuItem->icon = $button->getValueByKey('icon');
                                $menuItem->type = 'page';
                            }
                        );
                        $button->wireAlert(
                            $button->getValueByKey('name') . ' has been saved!',
                            'Menu',
                            AlertType::SUCCESS
                        );
                        if (!$id) {
                            $button->changeValue('name', '');
                            $button->changeValue('menuable_id', '');
                            $button->changeValue('icon', '');
                        }
                    })
                    ->when(function (Button $button) {
                        return $button->getWire()->menuId;
                    }),
                Button::make()->label('Remove')
                    ->icon('ti ti-trash')
                    ->className('btn btn-danger p-2 ms-2 mt-1')
                    ->confirm('Are you really want to remove?')
                    ->wireClick(function (Button $button) {
                        $id = $button->getValueByKey('id');
                        $button->getWire()->removeItemMenu($id);
                    })->when(function (Button $button) {
                        return $button->getValueByKey('id');
                    })
            ],
            UIKey::MENU_ITEM_TYPE->value . 'page',
            UIKey::MENU_ADD_ITEM->value,
            fn(Card $card) => $card->title('Pages')->className('mb-2')->hideBody()
                ->boot(function (Card $card) {
                    $card->groupField(null);
                    if (!$card->getValueByKey('id')) {
                        $card->groupField('pages');
                    }
                })
        );
    }

    public function packageBooted()
    {
        // packageBooted
    }
}
