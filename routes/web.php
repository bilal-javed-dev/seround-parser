<?php


use App\Http\Controllers\RssParserController;

Route::get('/rss', [RssParserController::class, 'index'])
     ->name('rss.index');
