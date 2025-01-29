<?php

use App\Http\Controllers\Api\SuggestionController;
use Illuminate\Support\Facades\Route;

Route::get('suggestion', SuggestionController::class)->name(name: 'suggestion.search');
