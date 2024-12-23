<?php

use App\Livewire\Counter;
use App\Livewire\WelcomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomePage::class);


Route::get('/counter', Counter::class);
