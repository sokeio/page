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
    public function extending()
    {
    }
    public function packageRegistered()
    {
        $this->extending();
        add_filter(PLATFORM_HOMEPAGE, function ($prev) {
            if ($pageId = setting('PLATFORM_HOMEPAGE')) {
                return ['uses' => PageView::class, 'params' => [
                    'page' => Page::query()->where('id', $pageId)->first()
                ]];
            }
            add_filter('SEO_DATA_DEFAULT', function ($prev) {
                return [
                    ...$prev,
                    'title' => setting('PLATFORM_HOMEPAGE_TITLE'),
                    'description' => setting('PLATFORM_HOMEPAGE_DESCRIPTION'),
                ];
            });
            return $prev;
        });

        Platform::Ready(function () {

            MenuRender::RegisterType(MenuItemPage::class);
            add_action('SEO_SITEMAP_INDEX', function () {
                foreach (['page'] as $type) {
                    Sitemap::addSitemap(route('sitemap_type', ['sitemap' => $type]));
                }
            });
            add_action('SEO_SITEMAP_PAGE', function ($sitemap) {
                $count = Page::query()->count();
                $maxPage = ceil($count / 1000) + 1;
                if($count<1000){
                    $maxPage = 1;
                }
                for ($page = 1; $page <= $maxPage; $page++) {
                    Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'page', 'page' => $page]));
                }
            });
            add_action('SEO_SITEMAP_PAGE_PAGE', function ($page) {
                foreach (Page::query()->latest()->paginate(1000, ['id', 'slug', 'created_at'], 'page', $page) as $tool) {
                    Sitemap::addItem($tool->getSeoCanonicalUrl(), $tool->created_at);
                };
            });
            if (page_with_builder()) {
                Livewire::component('page::page-builder', PageBuilder::class);
            }
            if (sokeio_is_admin()) {
                add_filter('SOKEIO_ADMIN_SETTING_OVERVIEW', function ($prev) {
                    return [
                        ...$prev,
                        UI::Column12([
                            UI::Select('PLATFORM_HOMEPAGE')->Label(__('Homepage'))->DataSource(function () {
                                return [
                                    [
                                        'id' => '',
                                        'name' => 'None'
                                    ],
                                    ...Page::query()->where('status', 'published')->get(['id', 'name'])
                                ];
                            })
                        ]),
                        UI::Column12([
                            UI::Text('PLATFORM_HOMEPAGE_TITLE')->Label(__('Homepage title'))
                        ]),
                        UI::Column12([
                            UI::Textarea('PLATFORM_HOMEPAGE_DESCRIPTION')->Label(__('Homepage Description'))
                        ]),
                    ];
                });
            }
            Menu::Register(function () {
                if (!sokeio_is_admin()) return;
                menu_admin()
                    ->route(['name' => 'admin.page', 'params' => []], 'Pages', '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-pagekit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M12.077 20h-5.077v-16h11v14h-5.077"></path>
         </svg>', [], 'admin.page');
            });
        });
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
            add_filter(PLATFORM_PERMISSION_CUSTOME, function ($prev) {
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
