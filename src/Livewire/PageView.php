<?php

namespace Sokeio\Page\Livewire;

use Illuminate\Support\Facades\View;
use Sokeio\Page\Models\Page;
use Sokeio\Component;
use Sokeio\Facades\Assets;

class PageView extends Component
{
    public ?Page $pageData;
    public function mount($page)
    {
        if (is_string($page)) {
            $this->pageData = Page::query()->withSlugKey($page)->firstOrFail();
        } elseif (is_numeric($page)) {
            $this->pageData = Page::query()->where('id', $page)->firstOrFail();
        } else {
            $this->pageData = $page;
        }
        if ($this->pageData->id == setting('PLATFORM_HOMEPAGE') && request()->path() != '/') {
            redirect(url('/'));
            return;
        }
        if ($this->pageData->id == setting('PLATFORM_HOMEPAGE')) {
            $this->pageData->setAssetLayout();
            Assets::setTitle(setting('PLATFORM_HOMEPAGE_TITLE'));
            Assets::setDescription(setting('PLATFORM_HOMEPAGE_DESCRIPTION'));
        } else {
            breadcrumb()->add(__('Home'), url(''));
            $this->pageData->setAssets();
        }
    }
    public function render()
    {
        if ($this->pageData->view_layout && View::exists($this->pageData->view_layout)) {
            return viewScope($this->pageData->view_layout, ['page' => $this->pageData]);
        }

        return viewScope('page::page', ['page' => $this->pageData]);
    }
}
