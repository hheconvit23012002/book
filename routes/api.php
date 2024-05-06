<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\GetUserLoginMiddleware;
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
    Route::post('/comment',[BookController::class,'comment'])->middleware([GetUserLoginMiddleware::class]);
    Route::post('/update/{id}',[BookController::class,'update']);
    Route::post('/delete/{id}',[BookController::class,'delete']);
    Route::post('/addNumber/{id}',[BookController::class,'addNumberProduct']);
    Route::post('/checkValidate',[BookController::class,'checkValidate']);
    Route::post('/updateProduct',[BookController::class,'updateProduct']);
    Route::get('/notPagingBook',[BookController::class,'notPagingBook']);
    Route::get('/getListProductInMonth',[BookController::class,'getListProductBuyMonth']);
    Route::get('/getProductUpdateInMonth',[BookController::class,'getProductUpdateInMonth']);
    Route::get('/{id}',[BookController::class,'get']);
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

Route::group([
    'prefix' => 'favourite',
    'middleware' => GetUserLoginMiddleware::class
], function (){
    Route::get('/getFavourite', [BookController::class,'getFavourite']);
    Route::post('/addFavorite',[BookController::class,'addToFavorite']);
    Route::post('/removeToFavorite',[BookController::class,'removeToFavorite']);
});

