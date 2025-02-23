<?php

namespace Sokeio\Page;

use Sokeio\Page\Models\Page;
use Sokeio\Theme;

// #[PageInfo(
//     route:'site.page',
//     url: '{slug?}',
//     model: \Sokeio\Page\Models\Page::class
// )]
class PageView extends \Sokeio\Page
{
    use WithSlugRender;
    protected $slugView = 'sokeio-page::pages.page.view';

    public function render()
    {
        $homepageId = setting('SOKEIO_SITE_HOMEPAGE');
        if (!$this->slug && !$homepageId) {
            return  Theme::view('sokeio-page::pages.homepage');
        }
        $page = null;
        if (!$this->slug) {
            $page = Page::find($homepageId);
            if ($page) {
                $page->title = setting('SOKEIO_SITE_TITLE', $page->title);
                $page->description = setting('SOKEIO_SITE_DESCRIPTION', $page->description);
            }
        }
        return SlugHelper::render($this->slug, $this->slugView, $this->getPageConfig()->getModel(), $page);
    }
}
