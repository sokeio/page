<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Page\Livewire\PageForm;
use Sokeio\Page\Livewire\PageTable;

Route::group(['as' => 'admin.'], function () {
    route_crud('page', PageTable::class, PageForm::class);
    if (page_with_builder()) {
        Route::get('page/create-builder', route_theme(Sokeio\Page\PageBuilder::class))->name('page.create-builder');
        Route::get('page/{dataId}/edit-builder', route_theme(Sokeio\Page\PageBuilder::class))->name('page.edit-builder');
    }
});
