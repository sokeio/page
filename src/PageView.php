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
        $homepageId = setting('SOKEIO_SITE_HOMEPAGE');
        if (!$this->slug && !$homepageId) {
            return  Theme::view('sokeio-page::pages.homepage');
        }
        $page = null;
        if ($this->slug) {
            $page =  Slug::findSluggableBySlug($this->slug, Page::class);

            if ($page?->id == $homepageId) {
                $this->redirectRoute('site.page');
            }
            Theme::title($page?->title);
            Theme::description($page?->description);
        } else {
            $page = Page::find($homepageId);
            Theme::title(setting('SOKEIO_SITE_TITLE', $page?->title));
            Theme::description(setting('SOKEIO_SITE_DESCRIPTION', $page?->description));
        }
        if (!$page) {
            return abort(404);
        }
        return Theme::view('sokeio-page::pages.page.view', [
            'page' => $page
        ], [], false, $page->template);
    }
}
