<?php

use App\Http\Controllers\Admin\CreatorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PriceUpdateController;
use App\Http\Controllers\Admin\SupportRequestController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GamesListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\RawgAPIController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupportRequestUserController;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {


    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/creators', [CreatorController::class, 'index'])->name('admin.games');
    Route::get('/creators/create', [CreatorController::class, 'create']);
    Route::post('/creators', [CreatorController::class, 'store']);
    Route::get('/creators/{id}', [CreatorController::class, 'show']);
    Route::get('/creators/{id}/edit', [CreatorController::class, 'edit']);
    Route::patch('/creators/{id}', [CreatorController::class, 'update']);
    Route::delete('/creators/{id}', [CreatorController::class, 'destroy']);

    Route::get('/games', [GameController::class, 'index'])->name('admin.games');
    Route::get('/games/create', [GameController::class, 'create']);
    Route::post('/games', [GameController::class, 'store']);
    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::get('/games/{id}/edit', [GameController::class, 'edit']);
    Route::patch('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);


    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);


    Route::get('/support', [\App\Http\Controllers\Admin\SupportRequestController::class, 'index'])->name('admin.support.index');
    Route::get('/support/{id}', [\App\Http\Controllers\Admin\SupportRequestController::class, 'show'])->name('admin.support.show');
    Route::get('/support/{id}/edit', [\App\Http\Controllers\Admin\SupportRequestController::class, 'edit'])->name('admin.support.edit');
    Route::patch('/support/{id}', [\App\Http\Controllers\Admin\SupportRequestController::class, 'update'])->name('admin.support.update');
    Route::delete('/support/{id}', [\App\Http\Controllers\Admin\SupportRequestController::class, 'destroy'])->name('admin.support.destroy');

    Route::get('/fetch-games', [RawgAPIController::class, 'fetchDataFromRawgAPI'])->name('admin.fetch-games');
    Route::get('/update-prices', [PriceUpdateController::class, 'updatePrices'])->name('admin.update.prices');

});


Route::middleware(['auth'])->group(function () {
    // ✅ Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ✅ Game Details (Authenticated Users Can See)
    //Route::get('/games/{id}', [RawgAPIController::class, 'show'])->name('game.show');
    Route::middleware(['auth'])->group(function () {

        Route::post('/cart/add/{game}', [CartController::class, 'add'])->name('cart.add');
        Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');
        Route::post('/cart/remove/{game}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');

        Route::get('/my-keys', [CartController::class, 'myKeys'])->name('user.keys');
        Route::delete('/keys/{key}', [App\Http\Controllers\CartController::class, 'destroy'])->name('user.keys.delete');
        Route::post('/keys/view/{key}', [App\Http\Controllers\CartController::class, 'markKeyViewed'])->name('user.keys.viewed');
        Route::delete('/keys/refund/{id}', [CartController::class, 'refund'])->name('user.keys.refund');

        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/toggle/{game}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

        Route::post('/reviews/{game}', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/games/{game}/average-rating', [GamesListController::class, 'getAverageRating']);

        Route::get('/games/{game}/review/edit', [ReviewController::class, 'edit'])->name('review.edit');
        Route::post('/games/{game}/review/update', [ReviewController::class, 'update'])->name('review.update');
        Route::get('/games/{game}/reviews', [GamesListController::class, 'getReviews'])->name('games.reviews');
        Route::delete('/games/{game}/review/delete', [ReviewController::class, 'destroy'])->name('review.destroy');

        Route::get('/support', [SupportRequestUserController::class, 'index'])->name('support.index');
        Route::get('/support/create', [SupportRequestUserController::class, 'create'])->name('support.create');
        Route::post('/support', [SupportRequestUserController::class, 'store'])->name('support.store');
        Route::get('/support/{id}', [SupportRequestUserController::class, 'show'])->name('support.show');
        Route::get('/support/{id}/edit', [SupportRequestUserController::class, 'edit'])->name('support.edit');
        Route::patch('/support/{id}', [SupportRequestUserController::class, 'update'])->name('support.update');
        Route::delete('/support/{id}', [SupportRequestUserController::class, 'destroy'])->name('support.destroy');
    });


});


Route::view('/', 'pages.home')->name('home');
/*Route::view('/games', 'pages.games')->name('games');
Route::view('/game/{id}', 'pages.game-single')->name('game.single');*/
Route::view('/reviews', 'pages.review')->name('reviews');
Route::view('/contact', 'pages.contact')->name('contact');
Route::get('/games', [GamesListController::class, 'listGames'])->name('games.list');
Route::get('/games/{id}', [GamesListController::class, 'show'])->name('games.show');
Route::post('/cart/add/{game}', [CartController::class, 'add'])->name('cart.add');

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');
});
