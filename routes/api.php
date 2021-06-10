<?php

use App\Http\Controllers\BirthdaysController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(function ()
{
//    Contacts
    Route::get('/contacts', [ContactsController::class, 'index']);
    Route::post('/contacts', [ContactsController::class, 'store']);
    Route::get('/contacts/{contact}', [ContactsController::class, 'show']);
    Route::patch('/contacts/{contact}', [ContactsController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactsController::class, 'destroy']);

//    Birthdays
    Route::get('/birthdays', [BirthdaysController::class, 'index']);

//    Search
    Route::post('/search', [SearchController::class, 'index']);
});
