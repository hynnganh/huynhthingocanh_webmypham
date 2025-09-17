<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\ProductController as ControllerSanPham;
use App\Http\Controllers\frontend\ContactController as ControllerLienHe;
use App\Http\Controllers\frontend\CategoryController as ControllerDanhMuc;
use App\Http\Controllers\frontend\PostController as ControllerBaiViet;

// ---------------- Frontend -----------------

// Home
Route::get('/', [HomeController::class, 'index'])->name('site.home');

// Product
Route::get('/san-pham', [ControllerSanPham::class, 'index'])->name('site.product');
Route::get('/san-pham/{slug}', [ControllerSanPham::class, 'detail'])->name('site.product-detail');
Route::get('/search', [ControllerSanPham::class, 'search'])->name('product.search');

// Contact
Route::get('/lien-he', [ControllerLienHe::class, 'index'])->name('site.contact');
Route::post('/lien-he', [ControllerLienHe::class, 'store'])->name('site.contact.store');

// Category
Route::get('/danh-muc/{slug}', [ControllerDanhMuc::class, 'showCategory'])->name('site.category.show');

// Post
Route::get('/bai-viet', [ControllerBaiViet::class, 'index'])->name('post.index');
Route::get('/bai-viet/{post}', [ControllerBaiViet::class, 'show'])->name('site.post.show');

// Static page (About/Blog)
Route::view('/gioi-thieu', 'frontend.blog')->name('site.blog');
Route::controller(ControllerNguoiDung::class)->group(function () {
    Route::get('/dang-nhap', 'showLoginForm')->name('login');
    Route::post('/dang-nhap', 'login');   
    Route::get('/dang-ky', 'showRegisterForm')->name('register');
    Route::post('/dang-ky', 'register');
    Route::post('/dang-xuat', 'logout')->name('logout');
    Route::get('/tai-khoan', 'account')->name('account');
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('add', [CartController::class, 'add'])->name('cart.add');
    Route::post('update', [CartController::class, 'update'])->name('cart.update');
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('store-order', [CartController::class, 'storeOrder'])->name('cart.storeOrder');
});
