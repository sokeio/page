<?php

namespace Sokeio\Page;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Sokeio\Components\UI;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Facades\Menu;
use Sokeio\Facades\MenuRender;
use Sokeio\Facades\Platform;
use Sokeio\Page\Livewire\MenuItemPage;
use Sokeio\Page\Livewire\PageView;
use Sokeio\Page\Models\Page;
use Sokeio\Seo\Facades\Sitemap;

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
            ->name('page')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    private function addTrigger()
    {
        addFilter(PLATFORM_HOMEPAGE, function ($prev) {
            if ($pageId = setting('PLATFORM_HOMEPAGE')) {
                return ['uses' => PageView::class, 'params' => [
                    'page' => Page::query()->where('id', $pageId)->first()
                ]];
            }
            addFilter('SEO_DATA_DEFAULT', function ($prev) {
                return [
                    ...$prev,
                    'title' => setting('PLATFORM_HOMEPAGE_TITLE'),
                    'description' => setting('PLATFORM_HOMEPAGE_DESCRIPTION'),
                ];
            });
            return $prev;
        });
        addAction('SEO_SITEMAP_INDEX', function () {
            foreach (['page'] as $type) {
                Sitemap::addSitemap(route('sitemap_type', ['sitemap' => $type]));
            }
        });
        addAction('SEO_SITEMAP_PAGE', function () {
            $count = Page::query()->count();
            $maxPage = ceil($count / 1000) + 1;
            if ($count < 1000) {
                $maxPage = 1;
            }
            for ($page = 1; $page <= $maxPage; $page++) {
                Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'page', 'page' => $page]));
            }
        });
        addAction('SEO_SITEMAP_PAGE_PAGE', function ($page) {
            $datas = Page::query()->latest()->paginate(1000, ['id', 'slug', 'created_at'], 'page', $page);
            foreach ($datas as $tool) {
                Sitemap::addItem($tool->getSeoCanonicalUrl(), $tool->created_at);
            }
        });
    }
    public function packageRegistered()
    {
        $this->addTrigger();
        Platform::ready(function () {
            MenuRender::RegisterType(MenuItemPage::class);

            if (sokeioIsAdmin()) {
                addFilter('SOKEIO_ADMIN_SETTING_OVERVIEW', function ($prev) {
                    return [
                        ...$prev,
                        UI::column12([
                            UI::select('PLATFORM_HOMEPAGE')->label(__('Homepage'))->dataSource(function () {
                                return [
                                    [
                                        'id' => '',
                                        'name' => 'None'
                                    ],
                                    ...Page::query()->where('status', 'published')->get(['id', 'name'])
                                ];
                            })
                        ]),
                        UI::column12([
                            UI::text('PLATFORM_HOMEPAGE_TITLE')->label(__('Homepage title'))
                        ]),
                        UI::column12([
                            UI::textarea('PLATFORM_HOMEPAGE_DESCRIPTION')->label(__('Homepage Description'))
                        ]),
                    ];
                });
                if (pageWithBuilder()) {
                    Livewire::component('page::page-builder', PageBuilder::class);
                }

                Menu::Register(function () {
                    menuAdmin()
                        ->route(
                            ['name' => 'admin.page', 'params' => []],
                            'Pages',
                            '<svg xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-brand-pagekit"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M12.077 20h-5.077v-16h11v14h-5.077"></path>
         </svg>',
                            [],
                            'admin.page'
                        );
                });
            }
        });
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
            addFilter(PLATFORM_PERMISSION_CUSTOME, function ($prev) {
                return [
                    ...$prev
                ];
            });
        }
    }
    public function packageBooted()
    {
        $this->bootGate();
    }
}
