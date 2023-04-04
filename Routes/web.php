<?php

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
use \Illuminate\Support\Facades\Route;

Route::prefix('')->middleware('auth')->group(function() {
    Route::resource('KnowledgeBaseCategory', 'KnowledgeBaseCategoryController');
    Route::post('KnowledgeBaseCategory-sort', 'KnowledgeBaseCategoryController@sort_item')->name('KnowledgeBaseCategory-sort');

    Route::resource('KnowledgeBase', 'KnowledgeBaseController');
    Route::get('KnowledgeBase-attach-delete/{id}', 'KnowledgeBaseController@attach_delete')->name('KnowledgeBase-attach-delete');
});
