<?php

namespace Sokeio\Page;


trait WithSlugRender
{
    public $slug;
    // protected $slugView = null;
    public function render()
    {
        return SlugHelper::render($this->slug, $this->slugView, $this->getPageConfig()->getModel());
    }
}
