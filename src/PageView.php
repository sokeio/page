<?php

namespace Sokeio\Page;

use Sokeio\Page\Models\Page;
use Sokeio\Page\Models\Slug;
use Sokeio\Theme;

// #[PageInfo(
//     route:'site.page',
//     url: '{slug?}',
// )]
class PageView extends \Sokeio\Page
{
    public $slug;

    public function render()
    {
        if (!$this->slug && setting('SOKEIO_SITE_HOMEPAGE') === null) {
            return  Theme::view('sokeio-page::pages.homepage');
        }
        $page = null;
        if ($this->slug) {
            $page =  Slug::findSluggableBySlug($this->slug, Page::class);
            Theme::title($page->title);
            Theme::description($page->description);
            if ($page->id == setting('SOKEIO_SITE_HOMEPAGE')) {
                $this->redirectRoute('site.page');
            }
        } else {
            $page = Page::find(setting('SOKEIO_SITE_HOMEPAGE'));
            Theme::title(setting('SOKEIO_SITE_TITLE', $page->title));
            Theme::description(setting('SOKEIO_SITE_DESCRIPTION', $page->description));
        }
        if (!$page) {
            return abort(404);
        }
        $viewPage = 'sokeio-page::pages.page.view';
        return Theme::view($viewPage, [
            'page' => $page
        ], [], false, $page->template);
    }
}
