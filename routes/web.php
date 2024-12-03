<?php

use App\Http\Controllers\SentimentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sentiment', [SentimentController::class, 'index'])->name('sentiment.analysis');
Route::post('/analyze', [SentimentController::class, 'analyze']);