<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\ProductController as ControllerSanPham;
use App\Http\Controllers\frontend\ContactController as ControllerLienHe;
use App\Http\Controllers\frontend\CartController; 
use App\Http\Controllers\frontend\CategoryController as ControllerDanhMuc; 
use App\Http\Controllers\frontend\PostController as ControllerBaiViet; 
use App\Http\Controllers\frontend\AuthController as ControllerNguoiDung;

use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\BannerController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\PostController;
use App\Http\Controllers\backend\TopicController;
use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\MenuController;
use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\OrderController;


Route::get('/ ', [HomeController::class, 'index'])->name('site.home');
Route::get('/san-pham', [ControllerSanPham::class, 'index'])->name('site.product');
Route::get('/san-pham/{slug}', [ControllerSanPham::class, 'detail'])->name('site.product-detail');
Route::get('/search', [ControllerSanPham::class, 'search'])->name('product.search');
Route::get('/lien-he', [ControllerLienHe::class, 'index'])->name('site.contact');
Route::post('/lien-he', [ControllerLienHe::class, 'store'])->name('site.contact.store');
Route::get('/bai-viet', [ControllerBaiViet::class, 'index'])->name('post.index');
Route::get('/bai-viet/{post}', [ControllerBaiViet::class, 'show'])->name('site.post.show');

Route::get('/danh-muc/{slug}', [ControllerDanhMuc::class, 'showCategory'])->name('site.category.show');

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




//ADMIN------------------------------------------------------------------------------------
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
 //Route:: prefix('admin') -> middleware('admin') ->group (function () {

 Route:: prefix('admin')->group (function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // product
    Route:: prefix('product') -> group (function () {
        Route::get('trash', [ProductController:: class, 'trash' ]) ->name ('product.trash');
        Route::get ('delete/{product}', [ProductController:: class, 'delete' ]) ->name ( 'product.delete');
        Route::get ('restore/{product}', [ProductController:: class, 'restore']) ->name ('product.restore');
        Route::get ('status/{product}', [ProductController:: class, 'status']) ->name ('product.status');
        Route::get('create', [ProductController::class, 'create'])->name('product.create');
        Route::get('show/{product}', [ProductController::class, 'show'])->name('product.show');

    });
    Route::resource ('product', ProductController::class);

    //banner
    Route::prefix('banner')->group(function () {
        Route::get('trash', [BannerController::class, 'trash'])->name('banner.trash');
        Route::get('delete/{banner}', [BannerController::class, 'delete'])->name('banner.delete');
        Route::get('restore/{banner}', [BannerController::class, 'restore'])->name('banner.restore');
        Route::get('status/{banner}', [BannerController::class, 'status'])->name('banner.status');
        Route::get('show/{banner}', [BannerController::class, 'show'])->name('banner.show');
        Route::get('create', [BannerController::class, 'create'])->name('banner.create');

    });
    Route::resource('banner', BannerController::class);
    
    //category
    Route::prefix('category')->group(function () {
        Route::get('trash', [CategoryController::class, 'trash'])->name('category.trash');
        Route::get('delete/{category}', [CategoryController::class, 'delete'])->name('category.delete');
        Route::get('restore/{category}', [CategoryController::class, 'restore'])->name('category.restore');
        Route::get('status/{category}', [CategoryController::class, 'status'])->name('category.status');
        Route::get('create', [CategoryController::class, 'create'])->name('category.create');
        Route::get('show/{category}', [CategoryController::class, 'show'])->name('category.show');

    });
    Route::resource('category', CategoryController::class);
    

    //brand
    Route::prefix('brand')->group(function () {
        Route::get('trash', [BrandController::class, 'trash'])->name('brand.trash');
        Route::get('delete/{brand}', [BrandController::class, 'delete'])->name('brand.delete');
        Route::get('restore/{brand}', [BrandController::class, 'restore'])->name('brand.restore');
        Route::get('status/{brand}', [BrandController::class, 'status'])->name('brand.status');
        Route::get('create', [BrandController::class, 'create'])->name('brand.create');
        Route::get('show/{brand}', [BrandController::class, 'show'])->name('brand.show');

    });
    Route::resource('brand', BrandController::class);
    
    //post
    Route::prefix('post')->group(function () {
        Route::get('trash', [PostController::class, 'trash'])->name('post.trash');
        Route::get('delete/{post}', [PostController::class, 'delete'])->name('post.delete');
        Route::get('restore/{post}', [PostController::class, 'restore'])->name('post.restore');
        Route::get('status/{post}', [PostController::class, 'status'])->name('post.status');
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::get('show/{post}', [PostController::class, 'show'])->name('post.show');

    });
    Route::resource('post', PostController::class);
    
    //topic
    Route::prefix('topic')->group(function () {
        Route::get('trash', [TopicController::class, 'trash'])->name('topic.trash');
        Route::get('delete/{topic}', [TopicController::class, 'delete'])->name('topic.delete');
        Route::get('restore/{topic}', [TopicController::class, 'restore'])->name('topic.restore');
        Route::get('status/{topic}', [TopicController::class, 'status'])->name('topic.status');
        Route::get('create', [TopicController::class, 'create'])->name('topic.create');
        Route::get('show/{topic}', [TopicController::class, 'show'])->name('topic.show');

    });
    Route::resource('topic', TopicController::class);
    
    //menu
    Route::prefix('menu')->group(function () {
        Route::get('trash', [MenuController::class, 'trash'])->name('menu.trash');
        Route::get('delete/{menu}', [MenuController::class, 'delete'])->name('menu.delete');
        Route::get('restore/{menu}', [MenuController::class, 'restore'])->name('menu.restore');
        Route::get('status/{menu}', [MenuController::class, 'status'])->name('menu.status');
        Route::get('create', [MenuController::class, 'create'])->name('menu.create');
        Route::get('show/{menu}', [MenuController::class, 'show'])->name('menu.show');

    });
    Route::resource('menu', MenuController::class);
    

    //contact
    Route::prefix('contact')->group(function () {
        Route::get('trash', [ContactController::class, 'trash'])->name('contact.trash');
        Route::get('delete/{contact}', [ContactController::class, 'delete'])->name('contact.delete');
        Route::get('restore/{contact}', [ContactController::class, 'restore'])->name('contact.restore');
        Route::get('status/{contact}', [ContactController::class, 'status'])->name('contact.status');
        Route::get('show/{contact}', [ContactController::class, 'show'])->name('contact.show');
        Route::get('reply/{contact}', [ContactController::class, 'reply'])->name('contact.reply');
    });
    Route::resource('contact', ContactController::class);
    
    //user
    Route::prefix('user')->group(function () {
        Route::get('trash', [UserController::class, 'trash'])->name('user.trash');
        Route::get('delete/{user}', [UserController::class, 'delete'])->name('user.delete');
        Route::get('restore/{user}', [UserController::class, 'restore'])->name('user.restore');
        Route::get('status/{user}', [UserController::class, 'status'])->name('user.status');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::get('show/{user}', [UserController::class, 'show'])->name('user.show');

    });
    Route::resource('user', UserController::class);
    
    //order
    Route::prefix('order')->group(function () {
        Route::get('trash', [OrderController::class, 'trash'])->name('order.trash');
        Route::get('delete/{order}', [OrderController::class, 'delete'])->name('order.delete');
        Route::get('restore/{order}', [OrderController::class, 'restore'])->name('order.restore');
        Route::post('{order}/status', [OrderController::class, 'status'])->name('order.status');
        Route::get('{order}/edit-status', [OrderController::class, 'editStatus'])->name('order.editStatus');
        Route::get('show/{order}', [OrderController::class, 'show'])->name('order.show');
    });
    Route::resource('order', OrderController::class);

});


Route::get('/', function () {
    return view('welcome');
});
