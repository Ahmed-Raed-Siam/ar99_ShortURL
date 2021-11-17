<?php

use App\Http\Controllers\Dashboard\LinkController;
use App\Http\Controllers\FrontSite\FrontSiteController;
use Illuminate\Support\Facades\Route;

/*--Front site controller--*/
Route::get('/', [FrontSiteController::class, 'showHome'])->name('frontSite.home');
Route::get('blog', [FrontSiteController::class, 'showBlog'])->name('frontSite.blog');
Route::get('single', [FrontSiteController::class, 'showSingle'])->name('frontSite.single');
Route::get('contact', [FrontSiteController::class, 'showContact'])->name('frontSite.contact');
/*--Public short link controller--*/
Route::get('ar99/{code}', [LinkController::class, 'public_shorten_link'])->name('shorten.link');

