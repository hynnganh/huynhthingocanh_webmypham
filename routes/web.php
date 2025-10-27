<?php

use Illuminate\Support\Facades\Route;

// ====================== FRONTEND CONTROLLERS ======================
use App\Http\Controllers\frontend\{
    HomeController,
    ProductController as FrontendProductController,
    ContactController as FrontendContactController,
    CartController,
    CategoryController as FrontendCategoryController,
    PostController as FrontendPostController,
    AuthController as FrontendAuthController,
    ReviewController
};
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\GeminiController;

// ====================== BACKEND CONTROLLERS ======================
use App\Http\Controllers\backend\{
    DashboardController,
    AuthController as BackendAuthController,
    ProductController as BackendProductController,
    BannerController as BackendBannerController,
    CategoryController as BackendCategoryController,
    PostController as BackendPostController,
    TopicController as BackendTopicController,
    BrandController as BackendBrandController,
    MenuController as BackendMenuController,
    ContactController as BackendContactController,
    UserController as BackendUserController,
    OrderController as BackendOrderController
};

// ================================================================
// 🏠 FRONTEND ROUTES
// ================================================================
Route::get('/', [HomeController::class, 'index'])->name('site.home');

// ----- SẢN PHẨM -----
Route::get('/san-pham', [FrontendProductController::class, 'index'])->name('site.product');
Route::get('/san-pham/{slug}', [FrontendProductController::class, 'detail'])->name('site.product-detail');
Route::get('/search', [FrontendProductController::class, 'search'])->name('product.search');
Route::get('/danh-muc/{slug}', [FrontendCategoryController::class, 'showCategory'])->name('site.category.show');

// ----- LIÊN HỆ -----
Route::get('/lien-he', [FrontendContactController::class, 'index'])->name('site.contact');
Route::post('/lien-he', [FrontendContactController::class, 'store'])->name('site.contact.store');

// ----- BÀI VIẾT -----
Route::get('/bai-viet', [FrontendPostController::class, 'index'])->name('site.post.index');
Route::get('/bai-viet/{post}', [FrontendPostController::class, 'show'])->name('site.post.show');

// ----- GIỚI THIỆU -----
Route::view('/gioi-thieu', 'frontend.blog')->name('site.blog');

// ================================================================
// 👤 FRONTEND AUTH
// ================================================================
Route::controller(FrontendAuthController::class)->group(function () {
    Route::get('/dang-nhap', 'showLoginForm')->name('login');
    Route::post('/dang-nhap', 'login');
    Route::get('/dang-ky', 'showRegisterForm')->name('register');
    Route::post('/dang-ky', 'register');
    Route::post('/dang-xuat', 'logout')->name('logout');

    // Trang tài khoản + đơn hàng
    Route::get('/tai-khoan', 'account')->name('account');
    Route::get('/tai-khoan/don-hang/{id}', 'orderDetail')->name('account.order.detail');
    Route::put('/account/update', 'update')->name('account.update');
});

// ----- KHÔI PHỤC MẬT KHẨU -----
Route::get('forgot-password', [FrontendAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [FrontendAuthController::class, 'sendResetCode'])->name('password.sendCode');
Route::get('reset-password', [FrontendAuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [FrontendAuthController::class, 'resetPassword'])->name('password.update');

// ================================================================
// 💬 CHAT AI (Gemini)
// ================================================================
Route::get('/chat-ai', [GeminiController::class, 'index'])->name('chat.ai.form');
Route::post('/chat-ai', [GeminiController::class, 'ask'])->name('chat.ai');
Route::get('/chat-ai/reset', [GeminiController::class, 'reset'])->name('chat.ai.reset');

// ================================================================
// ❤️ WISHLIST & ĐÁNH GIÁ
// ================================================================
Route::middleware('auth')->group(function () {
    Route::post('/san-pham/danh-gia', [ReviewController::class, 'store'])->name('review.store');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
});
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// ================================================================
// 🛒 CART & THANH TOÁN
// ================================================================
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('add', [CartController::class, 'add'])->name('cart.add');
    Route::post('update', [CartController::class, 'update'])->name('cart.update');
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow');
    Route::post('store-order', [CartController::class, 'storeOrder'])->name('cart.storeOrder');
    Route::post('store-order-online', [CartController::class, 'storeOrderOnline'])->name('cart.storeOrderOnline');
    Route::get('qr-code/{order}', [CartController::class, 'getQrCode'])->name('cart.qrCode');
    Route::post('confirm-payment/{order}', [CartController::class, 'confirmPayment'])->name('cart.confirmPayment');
});

// ================================================================
// ⚙️ ADMIN (CHỈ CHO ADMIN ĐÃ ĐĂNG NHẬP)
// ================================================================
// ==== LOGIN KHÔNG DÙNG MIDDLEWARE ====
    Route::get('admin/login', [BackendAuthController::class, 'showAdminLoginForm'])->name('admin.login.form');
    Route::post('admin/login', [BackendAuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('admin/logout', [BackendAuthController::class, 'logout'])->name('admin.logout');

    // ==== ADMIN DASHBOARD & MODULES ====
    Route::prefix('admin')->middleware('admin')->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Quản lý sản phẩm
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('trash', [BackendProductController::class, 'trash'])->name('trash');
            Route::get('delete/{product}', [BackendProductController::class, 'delete'])->name('delete');
            Route::get('restore/{product}', [BackendProductController::class, 'restore'])->name('restore');
            Route::get('status/{product}', [BackendProductController::class, 'status'])->name('status');
            Route::post('import', [BackendProductController::class, 'import'])->name('import');
        });
        Route::resource('product', BackendProductController::class)->except(['show']);

        // Các module khác giữ nguyên
        Route::resource('banner', BackendBannerController::class)->except(['show']);
        Route::get('banner/{banner}/status', [BackendBannerController::class, 'status'])->name('banner.status');

        Route::resource('category', BackendCategoryController::class)->except(['show']);
        Route::get('category/{category}/status', [BackendCategoryController::class, 'status'])->name('category.status');

        Route::resource('brand', BackendBrandController::class)->except(['show']);
        Route::get('brand/{brand}/status', [BackendBrandController::class, 'status'])->name('brand.status');

        Route::resource('post', BackendPostController::class)->except(['show']);
        Route::get('post/{post}/status', [BackendPostController::class, 'status'])->name('post.status');

        Route::resource('topic', BackendTopicController::class)->except(['show']);
        Route::get('topic/{topic}/status', [BackendTopicController::class, 'status'])->name('topic.status');

        Route::resource('menu', BackendMenuController::class)->except(['show']);
        Route::get('menu/{menu}/status', [BackendMenuController::class, 'status'])->name('menu.status');

        Route::resource('contact', BackendContactController::class)->except(['show']);
        Route::get('contact/{contact}/reply', [BackendContactController::class, 'reply'])->name('contact.reply');

        Route::resource('user', BackendUserController::class)->except(['show']);
        Route::get('user/{user}/status', [BackendUserController::class, 'status'])->name('user.status');

        Route::resource('order', BackendOrderController::class)->except(['create', 'edit']);
        Route::post('order/{order}/status', [BackendOrderController::class, 'status'])->name('order.status');
        Route::post('order/{order}/confirm-payment', [BackendOrderController::class, 'confirmPayment'])->name('order.confirmPayment');
    });

// ================================================================
// 🧪 TEST DATABASE
// ================================================================
Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return '✅ Kết nối database thành công!';
    } catch (\Exception $e) {
        return '❌ Lỗi DB: ' . $e->getMessage();
    }
});
