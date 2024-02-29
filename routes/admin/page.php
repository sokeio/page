<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Page\Livewire\PageForm;
use Sokeio\Page\Livewire\PageTable;
use Sokeio\Page\PageBuilder;

Route::group(['as' => 'admin.'], function () {
    route_crud('page', PageTable::class, PageForm::class);
    Route::get('page/create-builder', PageBuilder::class)->name('page.create-builder');
    Route::get('page/{dataId}/edit-builder', PageBuilder::class)->name('page.edit-builder');
});
