<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Page\Livewire\PageForm;
use Sokeio\Page\Livewire\PageTable;

Route::group(['as' => 'admin.'], function () {
    routeCrud('page', PageTable::class, PageForm::class);
    if (pageWithBuilder()) {
        Route::get('page/create-builder', Sokeio\Page\PageBuilder::class)->name('page.create-builder');
        Route::get('page/{dataId}/edit-builder', Sokeio\Page\PageBuilder::class)->name('page.edit-builder');
    }
});
