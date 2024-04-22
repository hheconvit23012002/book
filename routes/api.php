<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'book'
], function (){
    Route::get('/',[BookController::class,'index']);
    Route::post('/store',[BookController::class,'store']);
    Route::get('/{id}',[BookController::class,'get']);
    Route::post('/update/{id}',[BookController::class,'update']);
    Route::post('/delete/{id}',[BookController::class,'delete']);
    Route::post('/addNumber/{id}',[BookController::class,'addNumberProduct']);
});

Route::get('/books',[BookController::class,'index'])->name('books');
Route::group([
    'prefix' => 'category'
],function(){
    Route::get('/',[CategoryController::class,'index']);
    Route::post('/store',[CategoryController::class,'store']);
    Route::get('/{id}',[CategoryController::class,'get']);
    Route::post('/update/{id}',[CategoryController::class,'update']);
    Route::post('/delete/{id}',[CategoryController::class,'delete']);
});
