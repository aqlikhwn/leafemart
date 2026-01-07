<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\VariationController as AdminVariationController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [ProductController::class, 'browse'])->name('browse');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Demo Mode Setup Route
Route::get('/demo-setup', function () {
    $adminCreated = false;
    $customerCreated = false;
    
    // Create admin if not exists
    if (!\App\Models\User::where('email', 'admin@leafemart.com')->exists()) {
        \App\Models\User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@leafemart.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '0123456789',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $adminCreated = true;
    }
    
    // Create customer demo if not exists
    if (!\App\Models\User::where('email', 'aqilikhwan@gmail.com')->exists()) {
        \App\Models\User::create([
            'name' => 'Aqil Ikhwan (Demo)',
            'email' => 'aqilikhwan@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('qwertyuiop'),
            'phone' => '0198765432',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
        $customerCreated = true;
    }
    
    return redirect()->route('login')->with('success', 
        'Demo accounts ready! ' . 
        ($adminCreated ? 'Admin created. ' : 'Admin exists. ') .
        ($customerCreated ? 'Customer created.' : 'Customer exists.')
    );
})->name('demo.setup');
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Password Reset
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetCode'])->name('password.send.code');
    Route::get('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update');
    Route::post('/resend-reset-code', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resendCode'])->name('password.resend.code');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-code', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'sendCode'])->name('verification.send');
    Route::get('/email/verify-code', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'showVerifyForm'])->name('verification.verify.form');
    Route::post('/email/verify', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'resendCode'])->name('verification.resend');
});

// Authenticated customer routes
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('buy.now');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'deleteAccount'])->name('profile.delete');

    // Notifications
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [ProfileController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [ProfileController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/click', [ProfileController::class, 'clickNotification'])->name('notifications.click');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activities', [DashboardController::class, 'activities'])->name('activities.index');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::put('/orders/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.payment');

    // Product Variations
    Route::post('/variations', [AdminVariationController::class, 'store'])->name('variations.store');
    Route::put('/variations/{id}', [AdminVariationController::class, 'update'])->name('variations.update');
    Route::delete('/variations/{id}', [AdminVariationController::class, 'destroy'])->name('variations.destroy');

    // Messages
    Route::get('/messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/reply', [App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('messages.reply');

    // Users
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // FAQs
    Route::get('/faqs', [App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/create', [App\Http\Controllers\Admin\FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{id}/edit', [App\Http\Controllers\Admin\FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{id}', [App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{id}', [App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faqs.destroy');

    // Announcements
    Route::get('/announcements/create', [App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
});

// Contact form (public)
Route::post('/contact', [App\Http\Controllers\MessageController::class, 'store'])->name('contact.store');

// User messages (authenticated)
Route::get('/my-messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index')->middleware('auth');
