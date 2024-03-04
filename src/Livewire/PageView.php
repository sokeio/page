<?php

namespace Sokeio\Page\Livewire;

use Illuminate\Support\Facades\View;
use Sokeio\Page\Models\Page;
use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Theme;

class PageView extends Component
{
    public Page $page;
    public function mount()
    {
        if ($this->page->id == setting('PLATFORM_HOMEPAGE') && request()->path() != '/') {
            redirect(url('/'));
            return;
        }
        if ($this->page->layout) {
            Theme::setLayout($this->page->layout);
        }
        SeoHelper()->for($this->page);
        if ($this->page->css)
            Assets::AddStyle($this->page->css ?? '');
        if ($this->page->custom_css)
            Assets::AddStyle($this->page->custom_css ?? '');
        if ($this->page->js)
            Assets::AddScript($this->page->js ?? '');
        if ($this->page->custom_js)
            Assets::AddScript($this->page->custom_js ?? '');
        Assets::setTitle($this->page->name);

        if ($this->page->id == setting('PLATFORM_HOMEPAGE')) {
            Assets::setTitle(setting('PLATFORM_HOMEPAGE_TITLE'));
            Assets::setDescription(setting('PLATFORM_HOMEPAGE_DESCRIPTION'));
        } else {
            breadcrumb()->Add(__('Home'), url(''));
            breadcrumb()->Title($this->page->name);
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
