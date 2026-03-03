<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ListingController as OwnerListingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Auth Routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{listing}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});

// Marketplace Onboarding Routes
Route::middleware(['auth', 'role:owner,admin'])->prefix('onboarding')->name('owner.onboarding.')->group(function () {
    Route::get('/start', [\App\Http\Controllers\Owner\OnboardingController::class, 'start'])->name('start');
    Route::post('/start', [\App\Http\Controllers\Owner\OnboardingController::class, 'processStart']);
    Route::get('/wizard', [\App\Http\Controllers\Owner\OnboardingController::class, 'wizard'])->name('wizard');
    Route::post('/submit', [\App\Http\Controllers\Owner\OnboardingController::class, 'submit'])->name('submit');
});

// Owner Dashboard Routes
Route::middleware(['auth', 'role:owner,admin'])->prefix('dashboard')->name('owner.')->group(function () {
    Route::get('/', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/listings', [OwnerListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/create', [OwnerListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [OwnerListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [OwnerListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [OwnerListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [OwnerListingController::class, 'destroy'])->name('listings.destroy');

    // Owner Reviews
    Route::get('/reviews', [\App\Http\Controllers\Owner\ReviewController::class, 'index'])->name('reviews.index');

    // Support Tickets
    Route::get('/support', [\App\Http\Controllers\Owner\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [\App\Http\Controllers\Owner\SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [\App\Http\Controllers\Owner\SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{ticket}', [\App\Http\Controllers\Owner\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [\App\Http\Controllers\Owner\SupportController::class, 'reply'])->name('support.reply');

    // Inquiries
    Route::get('/inquiries', [\App\Http\Controllers\Owner\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [\App\Http\Controllers\Owner\InquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/reply', [\App\Http\Controllers\Owner\InquiryController::class, 'reply'])->name('inquiries.reply');
});

// Admin Panel Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
    Route::post('/listings/{listing}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
    Route::post('/listings/{listing}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');
    Route::patch('/listings/{listing}/toggle-featured', [AdminListingController::class, 'toggleFeatured'])->name('listings.toggle-featured');
    Route::patch('/listings/{listing}/toggle-premium', [AdminListingController::class, 'togglePremium'])->name('listings.toggle-premium');

    // User Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Review Management
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Support Tickets
    Route::get('/support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticket}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('support.reply');
    Route::patch('/support/{ticket}/status', [\App\Http\Controllers\Admin\SupportController::class, 'updateStatus'])->name('support.status');
});

// Auth Routes (Breeze) — must be before catch-all slug routes
require __DIR__.'/auth.php';

// Interactive/Public POST routes
Route::post('/listings/{listing}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('listing.review.store');
Route::post('/listings/{listing}/inquiry', [\App\Http\Controllers\InquiryController::class, 'store'])->name('listing.inquiry.store');

// Static Pages
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('page.about');
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('page.contact');
Route::post('/contact', [\App\Http\Controllers\PageController::class, 'contactSubmit'])->name('page.contact.submit');
Route::get('/privacy-policy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('page.privacy');
Route::get('/terms-of-service', [\App\Http\Controllers\PageController::class, 'terms'])->name('page.terms');

// Category & Listing Routes (must be last - these use slug routing)
Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/{category}/{slug}', [ListingController::class, 'show'])->name('listing.show');
