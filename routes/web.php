<?php

use Illuminate\Support\Facades\Route;

use App\Models\Listing;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Common Resource Routes:
// index - Show all listings
// show - Show single listing
// create - Show form to create new listing
// store - Store new listing
// edit - Show form to edit listing
// update - Update listing
// destroy - Delete listing

// all listings
Route::get('/', [ListingController::class, 'index']);

// show create form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// store new listing
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// manage listing route
Route::get('/listings/manage', [ListingController::class, 'manageListing'])->middleware('auth');

// show edit form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// update listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// delete/destroy listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

// single listing
Route::get('/listings/{listing}/', [ListingController::class, 'show']);

// create new user
Route::post('/users', [UserController::class, 'store']);

// show register/create form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// authenticate user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

// log user out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
