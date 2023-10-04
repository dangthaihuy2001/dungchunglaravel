<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ThumbImageController;
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

Route::get('/', function () {
    return view('home');
});

Route::get('news', function () {
    return view('news');
});
Route::get('editcourse', function () {
    return view('editcourse');
});

Route::get('course', [CourseController::class, 'index'])->middleware('per_page');
Route::get('editcourse/{id}', [CourseController::class, 'getCourseByID']);
Route::post('create-course', [CourseController::class, 'store'])->name('create-course');
Route::post('edit-course/{id}', [CourseController::class, 'editCourse'])->name('edit-course');
Route::get('delete/{id}', [CourseController::class, 'destroy'])->name('delete-course');



Route::get('thumbs/{w}x{h}x{z}/upload/{tem}/{slug}',[ThumbImageController::class, 'createThumb'] );


Route::fallback(function(){
    return 'Not found';
});
