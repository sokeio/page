<?php

namespace Sokeio\Page\Livewire;

use Illuminate\Support\Facades\View;
use Sokeio\Page\Models\Page;
use Sokeio\Component;
use Sokeio\Facades\Assets;

class PageView extends Component
{
    public Page $page;
    public function mount()
    {
        if ($this->page->id == setting('PLATFORM_HOMEPAGE') && request()->path() != '/') {
            redirect(url('/'));
            return;
        }
        if ($this->page->id == setting('PLATFORM_HOMEPAGE')) {
            $this->page->setAssetLayout();
            SeoHelper()->SEODataTransformer(function ($data) {
                $data['title'] = setting('PLATFORM_HOMEPAGE_TITLE');
                $data['description'] = setting('PLATFORM_HOMEPAGE_DESCRIPTION');
                return $data;
            });
            Assets::setTitle(setting('PLATFORM_HOMEPAGE_TITLE'));
            Assets::setDescription(setting('PLATFORM_HOMEPAGE_DESCRIPTION'));
        } else {
            breadcrumb()->add(__('Home'), url(''));
            $this->page->setAssets();
        }
    }
    public function render()
    {
        if ($this->page->view_layout && View::exists($this->page->view_layout)) {
            return view_scope($this->page->view_layout, ['page' => $this->page]);
        }

        return view_scope('page::page', ['page' => $this->page]);
    }
}
