<?php

use Illuminate\Support\Facades\Route;

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

Route::redirect('/', 'main/articles');

Route::view('me', 'me')->name('me');

Route::group([
    'prefix' => 'main',
    'as' => 'main.'
], function()
{
    Route::get('articles', [\App\Http\Controllers\Main\ArticleController::class, 'index'])->name('articles');
    Route::get('articles/{article:slug}', [\App\Http\Controllers\Main\ArticleController::class, 'show'])->name('articles.single');
    Route::get('articles/{category:slug}/category', \App\Http\Controllers\Main\Article\CategoryController::class)->name('articles.category');
    Route::get('articles/{tag:slug}/tag', \App\Http\Controllers\Main\Article\TagController::class)->name('articles.tag');
});


require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->middleware('is.admin')->name('users.index');
    
    Route::get('dashboard', \App\Http\Controllers\Author\DashboardController::class)->name('dashboard');

    Route::get('profile', [\App\Http\Controllers\Author\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\Author\ProfileController::class, 'update'])->name('profile.update');

    Route::group([
        'prefix' => 'author',
        'as' => 'author.'
    ], function ()
    {
        Route::get('articles/{category:slug}/category', \App\Http\Controllers\Author\Article\CategoryController::class)->name('articles.category');
        Route::get('articles/{tag:slug}/tag', \App\Http\Controllers\Author\Article\TagController::class)->name('articles.tag');
        Route::get('articles/{id}/restore', [\App\Http\Controllers\Author\ArticleController::class, 'restore'])->name('articles.restore');

        Route::resource('articles', \App\Http\Controllers\Author\ArticleController::class)->names('articles')->scoped([
            'article' => 'slug'
        ]);

        Route::group(['middleware' => 'is.admin'], function()
        {
            Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names('categories')->except('show')->scoped([
                'category' => 'slug'
            ]);
            Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->names('tags')->except('show')->scoped([
                'tag' => 'slug'
            ]);
        });
    });
});
