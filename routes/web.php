<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ListingController as OwnerListingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\ListingImportController as AdminListingImportController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Auth Routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Subscription / Plans
    Route::get('/plans', [\App\Http\Controllers\SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/plans/free', [\App\Http\Controllers\SubscriptionController::class, 'selectFree'])->name('subscription.free');

    // Tour guide dismiss
    Route::post('/tour/dismiss', [\App\Http\Controllers\SubscriptionController::class, 'dismissTour'])->name('tour.dismiss');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{listing}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Marketplace — selling (open to ANY logged-in user, not just owners)
    Route::get('/sell', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'create'])->name('marketplace.create');
    Route::post('/sell', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'store'])->name('marketplace.store');
    Route::get('/my-items', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'index'])->name('marketplace.my');
    Route::get('/my-items/{classified}/edit', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'edit'])->name('marketplace.edit');
    Route::put('/my-items/{classified}', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'update'])->name('marketplace.update');
    Route::post('/my-items/{classified}/sold', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'markSold'])->name('marketplace.sold');
    Route::delete('/my-items/{classified}', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'destroy'])->name('marketplace.destroy');
    Route::delete('/marketplace/images/{image}', [\App\Http\Controllers\Marketplace\SellerClassifiedController::class, 'destroyImage'])->name('marketplace.images.destroy');

    // User Bookings
    Route::get('/my-bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::post('/my-bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Trip Builder
    Route::get('/my-trips', [\App\Http\Controllers\TripController::class, 'index'])->name('trips.index');
    Route::post('/my-trips', [\App\Http\Controllers\TripController::class, 'store'])->name('trips.store');
    Route::post('/my-trips/add/{listing}', [\App\Http\Controllers\TripController::class, 'attachListing'])->name('trips.attach');
    Route::get('/my-trips/{trip:slug}', [\App\Http\Controllers\TripController::class, 'show'])->name('trips.show');
    Route::put('/my-trips/{trip:slug}', [\App\Http\Controllers\TripController::class, 'update'])->name('trips.update');
    Route::delete('/my-trips/{trip:slug}', [\App\Http\Controllers\TripController::class, 'destroy'])->name('trips.destroy');
    Route::delete('/my-trips/{trip:slug}/listings/{listing}', [\App\Http\Controllers\TripController::class, 'detachListing'])->name('trips.detach');
    Route::post('/my-trips/{trip:slug}/listings/{listing}/note', [\App\Http\Controllers\TripController::class, 'updateListingNote'])->name('trips.note');
    Route::post('/my-trips/{trip:slug}/listings/reorder', [\App\Http\Controllers\TripController::class, 'reorderListings'])->name('trips.reorder');
});

// Public trip share — read-only, no auth needed (token-protected URL).
Route::get('/trip/{token}', [\App\Http\Controllers\TripController::class, 'publicShow'])
    ->where('token', '[A-Za-z0-9]{24}')
    ->name('trips.public');

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
    Route::delete('/listings/images/{image}', [\App\Http\Controllers\Owner\ListingImageController::class, 'destroy'])->name('listings.images.destroy');

    // Owner Reviews
    Route::get('/reviews', [\App\Http\Controllers\Owner\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [\App\Http\Controllers\Owner\ReviewController::class, 'reply'])->name('reviews.reply');

    // Owner Bookings
    Route::get('/bookings', [\App\Http\Controllers\Owner\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Owner\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [\App\Http\Controllers\Owner\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/decline', [\App\Http\Controllers\Owner\BookingController::class, 'decline'])->name('bookings.decline');

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

    // Owner Profile
    Route::get('/profile', [\App\Http\Controllers\Owner\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Owner\ProfileController::class, 'update'])->name('profile.update');

    // Availability Calendar
    Route::get('/listings/{listing}/availability', [\App\Http\Controllers\Owner\AvailabilityController::class, 'index'])->name('availability.index');
    Route::put('/listings/{listing}/availability', [\App\Http\Controllers\Owner\AvailabilityController::class, 'update'])->name('availability.update');
});

// 2FA Routes (for admin users)
Route::middleware(['auth', 'role:admin'])->name('')->group(function () {
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/setup', [TwoFactorController::class, 'confirmSetup'])->name('2fa.setup.confirm');
    Route::get('/2fa/challenge', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
    Route::post('/2fa/challenge', [TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');
});

// Admin Panel Routes
Route::middleware(['auth', 'role:admin', '2fa'])->prefix(env('ADMIN_PREFIX', 'ha-control-2026'))->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');

    // CSV Bulk Import — must be before {listing} wildcard routes
    Route::get('/listings/import', [AdminListingImportController::class, 'index'])->name('listings.import');
    Route::post('/listings/import', [AdminListingImportController::class, 'store'])->name('listings.import.store');
    Route::post('/listings/{listing}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
    Route::get('/listings/{listing}/approve', function () {
        return redirect()->route('admin.listings.index', ['status' => 'pending'])
            ->with('error', 'To approve a listing, use the Approve button in the table below.');
    });
    Route::post('/listings/{listing}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');
    Route::get('/listings/{listing}/reject', function () {
        return redirect()->route('admin.listings.index', ['status' => 'pending'])
            ->with('error', 'To reject a listing, use the Reject button in the table below.');
    });
    Route::patch('/listings/{listing}/toggle-featured', [AdminListingController::class, 'toggleFeatured'])->name('listings.toggle-featured');
    Route::patch('/listings/{listing}/toggle-premium', [AdminListingController::class, 'togglePremium'])->name('listings.toggle-premium');
    Route::patch('/listings/{listing}/toggle-verified', [AdminListingController::class, 'toggleVerified'])->name('listings.toggle-verified');


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

    // Inquiries
    Route::get('/inquiries', [\App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [\App\Http\Controllers\Admin\InquiryController::class, 'show'])->name('inquiries.show');
    Route::delete('/inquiries/{inquiry}', [\App\Http\Controllers\Admin\InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // SEO
    Route::get('/seo', [\App\Http\Controllers\Admin\SeoController::class, 'index'])->name('seo.index');
    Route::get('/seo/{listing}/edit', [\App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('seo.edit');
    Route::put('/seo/{listing}', [\App\Http\Controllers\Admin\SeoController::class, 'update'])->name('seo.update');

    // Categories CRUD
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Areas CRUD
    Route::get('/areas', [\App\Http\Controllers\Admin\AreaController::class, 'index'])->name('areas.index');
    Route::get('/areas/create', [\App\Http\Controllers\Admin\AreaController::class, 'create'])->name('areas.create');
    Route::post('/areas', [\App\Http\Controllers\Admin\AreaController::class, 'store'])->name('areas.store');
    Route::get('/areas/{area}/edit', [\App\Http\Controllers\Admin\AreaController::class, 'edit'])->name('areas.edit');
    Route::put('/areas/{area}', [\App\Http\Controllers\Admin\AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{area}', [\App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('areas.destroy');

    // Blog Categories CRUD
    Route::get('/blog/categories', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'index'])->name('blog.categories.index');
    Route::get('/blog/categories/create', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'create'])->name('blog.categories.create');
    Route::post('/blog/categories', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'store'])->name('blog.categories.store');
    Route::get('/blog/categories/{category}/edit', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'edit'])->name('blog.categories.edit');
    Route::put('/blog/categories/{category}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'update'])->name('blog.categories.update');
    Route::delete('/blog/categories/{category}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'destroy'])->name('blog.categories.destroy');

    // Listing Tags CRUD
    Route::get('/tags', [\App\Http\Controllers\Admin\TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/create', [\App\Http\Controllers\Admin\TagController::class, 'create'])->name('tags.create');
    Route::post('/tags', [\App\Http\Controllers\Admin\TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{tag}/edit', [\App\Http\Controllers\Admin\TagController::class, 'edit'])->name('tags.edit');
    Route::put('/tags/{tag}', [\App\Http\Controllers\Admin\TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [\App\Http\Controllers\Admin\TagController::class, 'destroy'])->name('tags.destroy');

    // Blog Tags CRUD
    Route::get('/blog/tags', [\App\Http\Controllers\Admin\BlogTagController::class, 'index'])->name('blog.tags.index');
    Route::get('/blog/tags/create', [\App\Http\Controllers\Admin\BlogTagController::class, 'create'])->name('blog.tags.create');
    Route::post('/blog/tags', [\App\Http\Controllers\Admin\BlogTagController::class, 'store'])->name('blog.tags.store');
    Route::get('/blog/tags/{tag}/edit', [\App\Http\Controllers\Admin\BlogTagController::class, 'edit'])->name('blog.tags.edit');
    Route::put('/blog/tags/{tag}', [\App\Http\Controllers\Admin\BlogTagController::class, 'update'])->name('blog.tags.update');
    Route::delete('/blog/tags/{tag}', [\App\Http\Controllers\Admin\BlogTagController::class, 'destroy'])->name('blog.tags.destroy');

    // Blog Posts CRUD
    Route::get('/blog/posts', [\App\Http\Controllers\Admin\BlogPostController::class, 'index'])->name('blog.posts.index');
    Route::get('/blog/posts/create', [\App\Http\Controllers\Admin\BlogPostController::class, 'create'])->name('blog.posts.create');
    Route::post('/blog/posts', [\App\Http\Controllers\Admin\BlogPostController::class, 'store'])->name('blog.posts.store');
    Route::get('/blog/posts/{post}/edit', [\App\Http\Controllers\Admin\BlogPostController::class, 'edit'])->name('blog.posts.edit');
    Route::put('/blog/posts/{post}', [\App\Http\Controllers\Admin\BlogPostController::class, 'update'])->name('blog.posts.update');
    Route::delete('/blog/posts/{post}', [\App\Http\Controllers\Admin\BlogPostController::class, 'destroy'])->name('blog.posts.destroy');

    // Editorial Guides CRUD
    Route::get('/guides', [\App\Http\Controllers\Admin\GuideController::class, 'index'])->name('guides.index');
    Route::get('/guides/create', [\App\Http\Controllers\Admin\GuideController::class, 'create'])->name('guides.create');
    Route::post('/guides', [\App\Http\Controllers\Admin\GuideController::class, 'store'])->name('guides.store');
    Route::get('/guides/{guide}/edit', [\App\Http\Controllers\Admin\GuideController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [\App\Http\Controllers\Admin\GuideController::class, 'update'])->name('guides.update');
    Route::delete('/guides/{guide}', [\App\Http\Controllers\Admin\GuideController::class, 'destroy'])->name('guides.destroy');

    // Marketplace moderation + admin-added items
    Route::get('/classifieds', [\App\Http\Controllers\Admin\ClassifiedController::class, 'index'])->name('classifieds.index');
    Route::get('/classifieds/create', [\App\Http\Controllers\Admin\ClassifiedController::class, 'create'])->name('classifieds.create');
    Route::post('/classifieds', [\App\Http\Controllers\Admin\ClassifiedController::class, 'store'])->name('classifieds.store');
    Route::get('/classifieds/{classified}/edit', [\App\Http\Controllers\Admin\ClassifiedController::class, 'edit'])->name('classifieds.edit');
    Route::put('/classifieds/{classified}', [\App\Http\Controllers\Admin\ClassifiedController::class, 'update'])->name('classifieds.update');
    Route::post('/classifieds/{classified}/approve', [\App\Http\Controllers\Admin\ClassifiedController::class, 'approve'])->name('classifieds.approve');
    Route::post('/classifieds/{classified}/reject', [\App\Http\Controllers\Admin\ClassifiedController::class, 'reject'])->name('classifieds.reject');
    Route::post('/classifieds/{classified}/toggle-featured', [\App\Http\Controllers\Admin\ClassifiedController::class, 'toggleFeatured'])->name('classifieds.toggleFeatured');
    Route::delete('/classifieds/{classified}', [\App\Http\Controllers\Admin\ClassifiedController::class, 'destroy'])->name('classifieds.destroy');
});

// Auth Routes (Breeze) — must be before catch-all slug routes
require __DIR__.'/auth.php';

// Interactive/Public POST routes
Route::post('/listings/{listing}/reviews', [ReviewController::class, 'store'])->middleware(['auth', 'verified', 'throttle:3,1'])->name('listing.review.store');
Route::post('/listings/{listing}/inquiry', [\App\Http\Controllers\InquiryController::class, 'store'])->middleware('throttle:5,1')->name('listing.inquiry.store');
Route::post('/listings/{listing}/book', [\App\Http\Controllers\BookingController::class, 'store'])->middleware(['auth', 'verified', 'throttle:5,1'])->name('listing.book');
Route::post('/reviews/{review}/helpful', [ReviewController::class, 'helpful'])->middleware(['auth', 'throttle:10,1'])->name('review.helpful');

// Public Blog Routes
Route::get('/blog/feed', [\App\Http\Controllers\BlogController::class, 'feed'])->name('blog.feed');
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [\App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [\App\Http\Controllers\BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/author/{slug}', [\App\Http\Controllers\BlogController::class, 'author'])->name('blog.author');
Route::get('/blog/{post}/og-image', [\App\Http\Controllers\BlogController::class, 'ogImage'])->middleware('throttle:20,1')->name('blog.og-image');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post}/vote', [\App\Http\Controllers\BlogController::class, 'vote'])->middleware('throttle:10,1')->name('blog.vote');
Route::get('/sitemap-blog.xml', [\App\Http\Controllers\SitemapController::class, 'blog'])->name('sitemap.blog');

// Static Pages
Route::get('/about-us', [\App\Http\Controllers\PageController::class, 'about'])->name('page.about');
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('page.contact');
Route::post('/contact', [\App\Http\Controllers\PageController::class, 'contactSubmit'])->middleware('throttle:3,1')->name('page.contact.submit');
Route::get('/privacy-policy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('page.privacy');
Route::get('/terms-of-service', [\App\Http\Controllers\PageController::class, 'terms'])->name('page.terms');
Route::post('/newsletter/subscribe', [\App\Http\Controllers\PageController::class, 'newsletterSubscribe'])->middleware('throttle:3,1')->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [\App\Http\Controllers\PageController::class, 'newsletterConfirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\PageController::class, 'newsletterUnsubscribe'])->name('newsletter.unsubscribe');
Route::get('/emergency', [\App\Http\Controllers\PageController::class, 'emergency'])->name('page.emergency');
Route::get('/local-markets', [\App\Http\Controllers\PageController::class, 'localMarkets'])->name('page.local-markets');
Route::get('/how-to-reach', [\App\Http\Controllers\PageController::class, 'howToReach'])->name('page.how-to-reach');
Route::get('/weather', [\App\Http\Controllers\WeatherController::class, 'index'])->name('weather.index');
Route::get('/map', [\App\Http\Controllers\MapController::class, 'index'])->name('map.index');
Route::get('/map/markers.json', [\App\Http\Controllers\MapController::class, 'markers'])->middleware('throttle:60,1')->name('map.markers');

// Editorial guides — pillar content for SEO
Route::get('/guides', [\App\Http\Controllers\GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{guide:slug}', [\App\Http\Controllers\GuideController::class, 'show'])->name('guides.show');

// Events calendar
Route::get('/events-calendar', [\App\Http\Controllers\EventsController::class, 'index'])->name('events.calendar');

// "Best For" curated tag collections (registered before catch-all slug routes)
Route::get('/best-for/{tag:slug}', [\App\Http\Controllers\TagController::class, 'show'])->name('tag.show');

// Marketplace — public browsing (registered before catch-all slug routes)
Route::get('/marketplace', [\App\Http\Controllers\Marketplace\ClassifiedController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/item/{classified:slug}', [\App\Http\Controllers\Marketplace\ClassifiedController::class, 'show'])->name('marketplace.show');

// Category, Area & Listing Routes (must be last - these use slug routing)
Route::get('/area/{area:slug}', [\App\Http\Controllers\AreaController::class, 'show'])->name('area.show');

// Programmatic SEO landing pages: /{cat}-in-{area}, /{cat}-in-alibaug, /budget-{cat}-in-{area}, etc.
// Constrained to slugs containing "-in-" so it doesn't shadow plain category slugs.
Route::get('/{slug}', [\App\Http\Controllers\LandingController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*-in-[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('landing.show');

Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/{category}/{slug}', [ListingController::class, 'show'])->name('listing.show');
