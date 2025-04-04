<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BookController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function(): void{
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('forgot_password', 'forgotPassword');
    Route::get('password_reset', 'passwordReset');

});

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(UserController::class)->group(function(){
    Route::get('user', 'getUser');
    Route::post('user/upload_avatar', 'uploadAvatar');
    Route::delete('user/remove_avatar','removeAvatar');
    Route::post('user/send_verification_email','sendVerificationEmail');
    Route::post('user/change_email', 'changeEmail');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('books', BookController::class);
});
Route::controller(BookController::class)->group(function () {
    Route::post('books/{id}/update_book_picture', 'updateBookPicture');
    Route::delete('books/{id}', [BookController::class, 'destroy']);
});