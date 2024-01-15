<?php

use App\Http\Controllers\Api\CandidateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('candidates/filter', [CandidateController::class, 'filter'])
    //->middleware('auth:web')
    ->name('api.candidates.filter');
Route::post('candidates/contact/{candidate}', [CandidateController::class, 'contact'])->middleware('auth:web')->name('api.candidates.contact');
Route::patch('candidates/hire/{candidate}', [CandidateController::class, 'hire'])->middleware('auth:web')->name('api.candidates.hire');