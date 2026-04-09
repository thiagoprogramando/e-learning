<?php

use App\Http\Controllers\Access\ForgoutController;
use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\Access\RegisterController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Course\CatalogController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Finance\CouponController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Lesson\BlockController;
use App\Http\Controllers\Lesson\LessonController;
use App\Http\Controllers\Lesson\SubjectController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('logon', [LoginController::class, 'logon'])->name('logon');

Route::get('register/{indicator?}', [RegisterController::class, 'index'])->name('register');
Route::post('created-user', [RegisterController::class, 'store'])->name('created-user');

Route::get('/forgout/{code?}', [ForgoutController::class, 'index'])->name('forgout');
Route::post('/forgout-password', [ForgoutController::class, 'forgoutPassword'])->name('forgout-password');
Route::post('/recover-password/{code}', [ForgoutController::class, 'recoverPassword'])->name('recover-password');

Route::middleware(['auth'])->group(function () {

    Route::get('app', [AppController::class, 'index'])->name('app');

    Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('ava/{course}/{lesson?}/{block?}', [CatalogController::class, 'show'])->name('ava');
    Route::post('buy-course/{uuid}', [CatalogController::class, 'buyCourse'])->name('buy-course');

    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices');
    Route::post('created-invoice', [InvoiceController::class, 'store'])->name('created-invoice');
    Route::post('updated-invoice/{uuid}', [InvoiceController::class, 'update'])->name('updated-invoice');
    Route::post('deleted-invoice/{uuid}', [InvoiceController::class, 'destroy'])->name('deleted-invoice');

    Route::get('coupons', [CouponController::class, 'index'])->name('coupons');
    Route::post('created-coupon', [CouponController::class, 'store'])->name('created-coupon');
    Route::post('updated-coupon/{id}', [CouponController::class, 'update'])->name('updated-coupon');
    Route::post('deleted-coupon/{id}', [CouponController::class, 'destroy'])->name('deleted-coupon');

    Route::post('created-ticket', [TicketController::class, 'store'])->name('created-ticket');
    Route::post('updated-ticket/{uuid}', [TicketController::class, 'update'])->name('updated-ticket');
    Route::post('deleted-ticket/{uuid}', [TicketController::class, 'destroy'])->name('deleted-ticket');

    Route::get('courses', [CourseController::class, 'index'])->name('courses');
    Route::get('course/{uuid}', [CourseController::class, 'show'])->name('course');
    Route::post('created-course', [CourseController::class, 'store'])->name('created-course');
    Route::post('updated-course/{uuid}', [CourseController::class, 'update'])->name('updated-course');
    Route::post('deleted-course/{uuid}', [CourseController::class, 'destroy'])->name('deleted-course');
    Route::post('created-lesson-course/{id}', [CourseController::class, 'storeLesson'])->name('created-lesson-course');
    Route::post('deleted-lesson-course/{id}', [CourseController::class, 'destroyLesson'])->name('deleted-lesson-course');

    Route::get('lessons', [LessonController::class, 'index'])->name('lessons');
    Route::get('lesson/{uuid}', [LessonController::class, 'show'])->name('lesson');
    Route::post('created-lesson', [LessonController::class, 'store'])->name('created-lesson');
    Route::post('updated-lesson/{uuid}', [LessonController::class, 'update'])->name('updated-lesson');
    Route::post('deleted-lesson/{uuid}', [LessonController::class, 'destroy'])->name('deleted-lesson');

    Route::post('created-block/{uuid}', [BlockController::class, 'store'])->name('created-block');
    Route::post('deleted-block/{id}', [BlockController::class, 'destroy'])->name('deleted-block');

    Route::post('created-subject', [SubjectController::class, 'store'])->name('created-subject');
    Route::post('updated-subject/{id}', [SubjectController::class, 'update'])->name('updated-subject');
    Route::post('deleted-subject/{id}', [SubjectController::class, 'destroy'])->name('deleted-subject');

    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('user/{uuid}', [UserController::class, 'show'])->name('user');
    Route::post('created-user', [UserController::class, 'store'])->name('created-user');
    Route::post('updated-user/{uuid}', [UserController::class, 'update'])->name('updated-user');
    Route::post('deleted-user/{uuid}', [UserController::class, 'destroy'])->name('deleted-user');

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});
