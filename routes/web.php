<?php

use Illuminate\Support\Facades\Auth;
use Doctrine\Inflector\WordInflector;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LoadModalController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/loadWords/{wordToLoad}', [WordController::class, 'index']);

Route::get('/loadWordsOnRead/{readNumber}', [WordController::class, 'loadWordsOnRead']);
Route::get('/loadWordsOnArticle/{articleId}', [WordController::class, 'loadWordsOnArticle']);

Route::get('/load-articles/{articleId}', [ArticleController::class, 'index']);
Route::get('/view-articles', [ArticleController::class, 'view']);

Route::get('/load-numbers', [WordController::class, 'numbers']);
Route::get('/load-add-modal', [LoadModalController::class, 'addModal']);

Route::post('/save-word', [WordController::class, 'store']);
Route::post('/save-article', [ArticleController::class, 'store']);

Route::post('/update-article', [ArticleController::class, 'update']);


Route::post('/update-word', [WordController::class, 'update']);
Route::post('/mark-not-learned', [WordController::class, 'markNotLearned']);

Route::get('/search-word/{typedString}', [SearchController::class, 'index']);
Route::get('/date-search/{startDate}/{endDate}', [SearchController::class, 'dateSearch']);


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
