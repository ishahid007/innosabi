<?php

use App\Http\Controllers\Api\SuggestionController;
use Illuminate\Support\Facades\Route;

//
Route::group(['middleware' => 'throttle:api'], function () {
    Route::get('/suggestion', SuggestionController::class)->name('suggestion.search');
});
