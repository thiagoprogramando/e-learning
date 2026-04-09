<?php

use App\Http\Controllers\Finance\CouponController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('valited-coupon', [CouponController::class, 'valited'])->name('valited-coupon');
