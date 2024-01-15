<?php

use App\Http\Controllers\CandidateController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

if (Schema::hasTable('users') && User::count()) {
    \Auth::loginUsingId(User::whereHas('company')->first()->id);
    //\Auth::loginUsingId(User::whereDoesntHave('company')->first()->id);
    //\Auth::logout();
}


Route::get('/', function () {
    return view('homepage');
});

Route::get('candidates-list', [CandidateController::class, 'index']);
Route::post('candidates-contact', [CandidateController::class, 'contact']);

