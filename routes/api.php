<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\Api\BlogController;
use App\Http\Controllers\Frontend\Api\RankingController;
use App\Http\Controllers\Frontend\CassoWebhookController;
use App\Http\Controllers\Frontend\QRCodeController;
use App\Http\Controllers\Frontend\UserInfoController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\MainShopController;
use App\Http\Controllers\Admin\TimeSettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\Api\RechargeEventsController;
use App\Http\Controllers\Frontend\Api\RechargeEventGiftController;
use App\Http\Controllers\Frontend\Api\RankingEventGiftController;
use App\Http\Controllers\Frontend\Api\WeaponController;




// Public Routes
Route::post('/register', [UserController::class, 'apiRegister'])->name('api.apiRegister');
Route::post('/login', [UserController::class, 'apiLogin'])->name('api.apiLogin');

// Blog Routes
Route::get('/blogs', [BlogController::class, 'getBlogs'])->name('api.blogs.getBlogs');
Route::get('/blogs/{slug}', [BlogController::class, 'getBlogDetail'])->name('api.blogs.getBlogDetail');
Route::get('/blogs/category/{id}', [BlogController::class, 'getBlogsByCategory'])->name('api.blogs.getBlogsByCategory');

// Public Ranking Routes
Route::prefix('rankings')->group(function() {
    Route::get('/', [RankingController::class, 'getRankings'])->name('api.rankings.getRankings');
    Route::get('/level', [RankingController::class, 'getLevelRankings'])->name('api.rankings.getLevelRankings');
    Route::get('/gold', [RankingController::class, 'getGoldRankings'])->name('api.rankings.getGoldRankings');
    Route::get('/honor', [RankingController::class, 'getHonorRankings'])->name('api.rankings.getHonorRankings');
    Route::get('/gong', [RankingController::class, 'getGongRankings'])->name('api.rankings.getGongRankings');
    Route::get('/top-recharge', [RankingController::class, 'getTopRechargeRankings']);
    Route::get('/top-spend', [RankingController::class, 'getTopSpendRankings']);
    Route::get('/event-countdowns', [RankingController::class, 'getEventCountdowns']);
    Route::get('/search', [RankingController::class, 'getRankings'])->name('api.rankings.search');
});

// Webhook Routes
Route::post('/webhook/casso', [CassoWebhookController::class, 'handle'])
    ->name('casso.webhook')
    ->middleware('api');

// Password Reset & Account Activation Routes
Route::prefix('password')->group(function() {
    Route::post('/email', 'Frontend\PasswordResetController@create')->name('password.email');
    Route::get('/reset/{token}', 'Frontend\PasswordResetController@find')->name('password.reset');
    Route::post('/reset', 'Frontend\PasswordResetController@reset')->name('password.update');
    Route::get('/check-token/{token}', 'Frontend\PasswordResetController@checkToken')->name('password.check');
    Route::post('/verify-code', 'Frontend\PasswordResetController@verifyCode')->name('password.verify');
});

Route::post('/send-activation-mail', [UserController::class, 'sendActivationMail'])->name('send.activation.mail');
Route::get('/activate-account/{token}', [UserController::class, 'activateAccount'])->name('activate.account');
Route::get('/activate-account/{token}', [UserController::class, 'activateAccountAPI'])->name('api.activate.account');
Route::get('/check-activation-status/{email}', [UserController::class, 'checkActivationStatus']);
Route::post('/verify-activation-code', [UserController::class, 'verifyActivationCode']);

// Settings Routes
Route::get('/time-settings', [TimeSettingController::class, 'getTimeSettings']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    // User Routes
    Route::get('/user', [UserController::class, 'getCurrentUser'])->name('api.user');
    Route::post('/logout', [UserController::class, 'logout'])->name('api.logout');
    Route::post('/reset-password', [UserController::class, 'resetPass'])->name('api.reset-password');
    Route::get('/coin-balance', [UserController::class, 'getCoinBalance'])->name('api.coin-balance');
    Route::get('/current-coins', [UserController::class, 'getCurrentCoins'])->name('api.current-coins');
    Route::get('/get-coin-balance', [UserController::class, 'getCoinBalance']);
    Route::get('/get-current-coins', [UserController::class, 'getCurrentCoins']);
    Route::get('/get-user-info', function () {
        return Auth::user()->only(['coin']);
    });

    // Transaction History Route - Đưa ra khỏi prefix user-info
    Route::get('/transaction-history', [UserInfoController::class, 'getTransactionHistory']);

    // User Info Routes
    Route::prefix('user-info')->group(function() {
        Route::post('/claim-gift', [UserInfoController::class, 'claimGift']);
        Route::get('/daily-gifts', [UserInfoController::class, 'getDailyGifts']);
        Route::get('/user-characters', [UserInfoController::class, 'getUserCharacters']);
        Route::get('/get-vip-info', [UserInfoController::class, 'getVipInfoAjax']);
    });

    // Shop Routes
    Route::prefix('shop')->group(function() {
        Route::get('/items', [MainShopController::class, 'getShopItems']);
        Route::get('/category/{id}', [MainShopController::class, 'getItemsByCategory']);
        Route::post('/buy', [MainShopController::class, 'buyItem']);
        Route::get('/accumulate-items', [MainShopController::class, 'getAccumulateItems']);
        Route::post('/buy-accumulate', [MainShopController::class, 'buyWithAccumulate']);
    });

    // Ranking Events Routes
    Route::prefix('ranking-events')->group(function() {
        Route::get('/rewards/{type}', [RankingEventGiftController::class, 'getRankingRewards']);
        Route::get('/user-status/{type}', [RankingEventGiftController::class, 'getUserRankStatus']);
        Route::post('/claim', [RankingEventGiftController::class, 'claimRankingReward']);
    });

    // Recharge Events Routes 
    Route::prefix('recharge-events')->group(function() {
        Route::get('/config', [RechargeEventsController::class, 'getConfig']);
        Route::get('/user-status', [RechargeEventsController::class, 'getUserRechargeStatus']);
        Route::post('/claim-first-recharge', [RechargeEventGiftController::class, 'claimFirstRechargeGift']);
        Route::post('/claim-milestone', [RechargeEventGiftController::class, 'claimMilestoneGift']);
    });

    // Gift Code Routes
    Route::post('/claim-giftcode', [IndexController::class, 'claimGiftCode']);

    // QR Code Routes
    Route::post('/generate-qr-code', [QRCodeController::class, 'generateQRCode'])
        ->name('api.generate.qr.code');
			
});

// Admin Routes
Route::prefix('admin')->group(function() {
    Route::get('product/daily-gift-status/{id}/{status}', [ProductController::class, 'dailyGiftStatus'])
        ->name('admin.product.dailyGiftStatus');
        
    Route::get('product/daily-gift-type/{id}/{type}', [ProductController::class, 'dailyGiftType'])
        ->name('admin.product.dailyGiftType');
});
// Weapon routes
Route::middleware('auth:api')->group(function() {
    Route::get('/user-characters', 'Frontend\Api\WeaponController@getUserCharacters');
    Route::get('/character-weapons/{characterId}', 'Frontend\Api\WeaponController@getCharacterWeapons');
    Route::post('/change-weapon', 'Frontend\Api\WeaponController@changeWeapon');
    
});
// Route test
Route::middleware('auth:api')->get('/test-weapon-tables/{characterId}', 'Frontend\Api\WeaponController@testWeaponTables');