<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\ProductController as FrontendProductController;
use App\Http\Controllers\frontend\ContactController as FrontendContactController;
use App\Http\Controllers\frontend\CartController; 
use App\Http\Controllers\frontend\CategoryController as FrontendCategoryController; 
use App\Http\Controllers\frontend\PostController as FrontendPostController; 
use App\Http\Controllers\frontend\AuthController as FrontendAuthController;

use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\AuthController as BackendAuthController;
use App\Http\Controllers\backend\ProductController as BackendProductController;
use App\Http\Controllers\backend\BannerController as BackendBannerController;
use App\Http\Controllers\backend\CategoryController as BackendCategoryController;
use App\Http\Controllers\backend\PostController as BackendPostController;
use App\Http\Controllers\backend\TopicController as BackendTopicController;
use App\Http\Controllers\backend\BrandController as BackendBrandController;
use App\Http\Controllers\backend\MenuController as BackendMenuController;
use App\Http\Controllers\backend\ContactController as BackendContactController;
use App\Http\Controllers\backend\UserController as BackendUserController;
use App\Http\Controllers\backend\OrderController as BackendOrderController;

// ====================== FRONTEND ======================
Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/san-pham', [FrontendProductController::class, 'index'])->name('site.product');
Route::get('/san-pham/{slug}', [FrontendProductController::class, 'detail'])->name('site.product-detail');
Route::get('/search', [FrontendProductController::class, 'search'])->name('product.search');

Route::get('/lien-he', [FrontendContactController::class, 'index'])->name('site.contact');
Route::post('/lien-he', [FrontendContactController::class, 'store'])->name('site.contact.store');

Route::get('/bai-viet', [FrontendPostController::class, 'index'])->name('post.index');
Route::get('/bai-viet/{post}', [FrontendPostController::class, 'show'])->name('site.post.show');

Route::get('/danh-muc/{slug}', [FrontendCategoryController::class, 'showCategory'])->name('site.category.show');
Route::view('/gioi-thieu', 'frontend.blog')->name('site.blog');

// ====================== FRONTEND AUTH ======================
Route::controller(FrontendAuthController::class)->group(function () {
    Route::get('/dang-nhap', 'showLoginForm')->name('login');
    Route::post('/dang-nhap', 'login');   
    Route::get('/dang-ky', 'showRegisterForm')->name('register');
    Route::post('/dang-ky', 'register');
    Route::post('/dang-xuat', 'logout')->name('logout');
    Route::get('/tai-khoan', 'account')->name('account');
});

// ====================== CART ======================
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('add', [CartController::class, 'add'])->name('cart.add');
    Route::post('update', [CartController::class, 'update'])->name('cart.update');
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::post('store-order', [CartController::class, 'storeOrder'])->name('cart.storeOrder');
    Route::post('store-order-online', [CartController::class, 'storeOrderOnline'])->name('cart.storeOrderOnline');

    // Lấy QR code
    Route::get('qr-code/{order}', [CartController::class, 'getQrCode'])->name('cart.qrCode');

    // ✅ Xác nhận đã chuyển tiền (POST)
    Route::post('confirm-payment/{order}', [CartController::class, 'confirmPayment'])
        ->name('cart.confirmPayment');
});

// ====================== ADMIN AUTH ======================
Route::get('/admin/login', [BackendAuthController::class, 'showAdminLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [BackendAuthController::class, 'adminLogin'])->name('admin.login');
Route::get('/admin/logout', [BackendAuthController::class, 'logout'])->name('admin.logout');

// ====================== ADMIN ======================
Route::prefix('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ----- PRODUCT -----
    Route::prefix('product')->group(function () {
        Route::get('trash', [BackendProductController::class, 'trash'])->name('product.trash');
        Route::get('delete/{product}', [BackendProductController::class, 'delete'])->name('product.delete');
        Route::get('restore/{product}', [BackendProductController::class, 'restore'])->name('product.restore');
        Route::get('status/{product}', [BackendProductController::class, 'status'])->name('product.status');
    });
    Route::resource('product', BackendProductController::class);

    // ----- BANNER -----
    Route::prefix('banner')->group(function () {
        Route::get('trash', [BackendBannerController::class, 'trash'])->name('banner.trash');
        Route::get('delete/{banner}', [BackendBannerController::class, 'delete'])->name('banner.delete');
        Route::get('restore/{banner}', [BackendBannerController::class, 'restore'])->name('banner.restore');
        Route::get('status/{banner}', [BackendBannerController::class, 'status'])->name('banner.status');
    });
    Route::resource('banner', BackendBannerController::class);

    // ----- CATEGORY -----
    Route::prefix('category')->group(function () {
        Route::get('trash', [BackendCategoryController::class, 'trash'])->name('category.trash');
        Route::get('delete/{category}', [BackendCategoryController::class, 'delete'])->name('category.delete');
        Route::get('restore/{category}', [BackendCategoryController::class, 'restore'])->name('category.restore');
        Route::get('status/{category}', [BackendCategoryController::class, 'status'])->name('category.status');
    });
    Route::resource('category', BackendCategoryController::class);

    // ----- BRAND -----
    Route::prefix('brand')->group(function () {
        Route::get('trash', [BackendBrandController::class, 'trash'])->name('brand.trash');
        Route::get('delete/{brand}', [BackendBrandController::class, 'delete'])->name('brand.delete');
        Route::get('restore/{brand}', [BackendBrandController::class, 'restore'])->name('brand.restore');
        Route::get('status/{brand}', [BackendBrandController::class, 'status'])->name('brand.status');
    });
    Route::resource('brand', BackendBrandController::class);

    // ----- POST -----
    Route::prefix('post')->group(function () {
        Route::get('trash', [BackendPostController::class, 'trash'])->name('post.trash');
        Route::get('delete/{post}', [BackendPostController::class, 'delete'])->name('post.delete');
        Route::get('restore/{post}', [BackendPostController::class, 'restore'])->name('post.restore');
        Route::get('status/{post}', [BackendPostController::class, 'status'])->name('post.status');
    });
    Route::resource('post', BackendPostController::class);

    // ----- TOPIC -----
    Route::prefix('topic')->group(function () {
        Route::get('trash', [BackendTopicController::class, 'trash'])->name('topic.trash');
        Route::get('delete/{topic}', [BackendTopicController::class, 'delete'])->name('topic.delete');
        Route::get('restore/{topic}', [BackendTopicController::class, 'restore'])->name('topic.restore');
        Route::get('status/{topic}', [BackendTopicController::class, 'status'])->name('topic.status');
    });
    Route::resource('topic', BackendTopicController::class);

    // ----- MENU -----
    Route::prefix('menu')->group(function () {
        Route::get('trash', [BackendMenuController::class, 'trash'])->name('menu.trash');
        Route::get('delete/{menu}', [BackendMenuController::class, 'delete'])->name('menu.delete');
        Route::get('restore/{menu}', [BackendMenuController::class, 'restore'])->name('menu.restore');
        Route::get('status/{menu}', [BackendMenuController::class, 'status'])->name('menu.status');
    });
    Route::resource('menu', BackendMenuController::class);

    // ----- CONTACT -----
    Route::prefix('contact')->group(function () {
        Route::get('trash', [BackendContactController::class, 'trash'])->name('contact.trash');
        Route::get('delete/{contact}', [BackendContactController::class, 'delete'])->name('contact.delete');
        Route::get('restore/{contact}', [BackendContactController::class, 'restore'])->name('contact.restore');
        Route::get('status/{contact}', [BackendContactController::class, 'status'])->name('contact.status');
        Route::get('reply/{contact}', [BackendContactController::class, 'reply'])->name('contact.reply');
    });
    Route::resource('contact', BackendContactController::class);

    // ----- USER -----
    Route::prefix('user')->group(function () {
        Route::get('trash', [BackendUserController::class, 'trash'])->name('user.trash');
        Route::get('delete/{user}', [BackendUserController::class, 'delete'])->name('user.delete');
        Route::get('restore/{user}', [BackendUserController::class, 'restore'])->name('user.restore');
        Route::get('status/{user}', [BackendUserController::class, 'status'])->name('user.status');
    });
    Route::resource('user', BackendUserController::class);

    // ----- ORDER -----
    Route::prefix('order')->group(function () {
        Route::get('trash', [BackendOrderController::class, 'trash'])->name('order.trash');
        Route::get('delete/{order}', [BackendOrderController::class, 'delete'])->name('order.delete');
        Route::get('restore/{order}', [BackendOrderController::class, 'restore'])->name('order.restore');
        Route::post('{order}/status', [BackendOrderController::class, 'status'])->name('order.status');
        Route::get('{order}/edit-status', [BackendOrderController::class, 'editStatus'])->name('order.editStatus');
        Route::post('{order}/confirm-payment', [BackendOrderController::class, 'confirmPayment'])->name('order.confirmPayment');
    });
    Route::resource('order', BackendOrderController::class);

});
