<?php

use App\Http\Controllers\FrontSite\FrontSiteController;
use Illuminate\Support\Facades\Route;

/*--NEW--*/
Route::get('/', [FrontSiteController::class, 'showHome'])->name('frontSite.home');
Route::get('blog', [FrontSiteController::class, 'showBlog'])->name('frontSite.blog');
Route::get('single', [FrontSiteController::class, 'showSingle'])->name('frontSite.single');
Route::get('contact', [FrontSiteController::class, 'showContact'])->name('frontSite.contact');