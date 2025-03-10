<?php
use App\Http\Controllers\Frontend\CassoWebhookController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\RealtimeNotificationController;
use App\Http\Controllers\Frontend\TestPusherController;
use App\Http\Controllers\Frontend\PusherConfigController;
use App\Http\Controllers\Frontend\QRCodeController;
use App\Http\Controllers\Frontend\ProductController;
use Illuminate\Support\Facades\Broadcast;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
 return 'Sever đang bảo trì, vui lòng quay lại sau';
});*/


Route::get('loginAdmin', 'Admin\UserController@getLogin');
Route::post('loginAdmin', 'Admin\UserController@postLogin');
Route::get('logoutAdmin', 'Admin\UserController@getLogout');

Route::group(['prefix' => 'admins', 'namespace' => 'Admin', 'middleware' => 'loginAdmin'], function () {
    Route::group(['prefix' => 'product-list'], function () {
        Route::group(['prefix' => 'category-product'], function () {
            Route::get('/', 'CategoryProductController@index')->name('admin.cateProduct.index');
            Route::get('/add', 'CategoryProductController@add')->name('admin.cateProduct.add');
            Route::post('/add', 'CategoryProductController@store')->name('admin.cateProduct.store');
            Route::get('/edit/{id}', 'CategoryProductController@edit')->name('admin.cateProduct.edit');
            Route::post('/edit/{id}', 'CategoryProductController@update')->name('admin.cateProduct.update');
            Route::get('/delete/{id}', 'CategoryProductController@delete')->name('admin.cateProduct.delete');
            Route::get('/status/{id}/{status}', 'CategoryProductController@status')->name('admin.cateProduct.status');
        });
        
        Route::get('/recharge-events', 'RechargeEventsController@index')->name('admin.rechargeEvents.index');
        Route::post('/recharge-events', 'RechargeEventsController@update')->name('admin.rechargeEvents.update');
        
        Route::post('/recharge-events/first-recharge', 'RechargeEventsController@updateFirstRecharge')
        ->name('admin.rechargeEvents.updateFirstRecharge');
        Route::post('/recharge-events/milestone', 'RechargeEventsController@updateMilestone')
        ->name('admin.rechargeEvents.updateMilestone');
        Route::post('/recharge-events/golden-hour', 'RechargeEventsController@updateGoldenHour')
        ->name('admin.rechargeEvents.updateGoldenHour');


        Route::group(['prefix' => 'ranking-event'], function () {
            Route::get('/', 'RankingEventController@index')->name('admin.rankingEvent.index');
            Route::post('/update', 'RankingEventController@update')->name('admin.rankingEvent.update');
            Route::post('/update-rewards/{type}', 'RankingEventController@updateRewards')
                ->name('admin.rankingEvent.updateRewards');
        });     
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'ProductController@index')->name('admin.product.index');
            Route::get('/add', 'ProductController@add')->name('admin.product.add');
            Route::post('/add', 'ProductController@store');
            Route::get('/edit/{id}', 'ProductController@edit')->name('admin.product.edit');
            Route::post('/edit/{id}', 'ProductController@update');
            Route::get('/delete/{id}', 'ProductController@delete')->name('admin.product.delete');
            Route::get('/status/{id}/{status}', 'ProductController@status')->name('admin.product.status');
            Route::get('/luckyStatus/{id}/{status}', 'ProductController@luckyStatus')->name('admin.product.luckyStatus');
            Route::get('/accumulateStatus/{id}/{status}', 'ProductController@accumulateStatus')->name('admin.product.accumulateStatus');
            Route::get('/hot/{id}/{hot}', 'ProductController@hot')->name('admin.product.hot');
            Route::get('/active/{id}/{active}', 'ProductController@active')->name('admin.product.active');
        });
    });
    Route::group(['prefix' => 'time-setting'], function () {
        Route::get('/', 'TimeSettingController@index')->name('admin.timeSetting.index');
        Route::get('/edit/{id}', 'TimeSettingController@edit')->name('admin.timeSetting.edit');
        Route::post('/edit/{id}', 'TimeSettingController@update')->name('admin.timeSetting.update');
    });

    Route::group(['prefix' => 'gift'], function () {
        Route::group(['prefix' => 'gift-code'], function () {
            Route::get('/', 'GiftCodeController@index')->name('admin.giftCode.index');
            Route::get('/add', 'GiftCodeController@add')->name('admin.giftCode.add');
            Route::post('/add', 'GiftCodeController@store')->name('admin.giftCode.store');
            Route::get('/edit/{id}', 'GiftCodeController@edit')->name('admin.giftCode.edit');
            Route::post('/edit/{id}', 'GiftCodeController@update')->name('admin.giftCode.update');
            Route::get('/delete/{id}', 'GiftCodeController@delete')->name('admin.giftCode.delete');
            //            Route::get('/status/{id}/{status}','CategoryBlogController@status')->name('admin.cateBlog.status');
        });
        Route::group(['prefix' => 'gift-code-history'], function () {
            Route::get('/', 'GiftCodeHistoryController@index')->name('admin.giftCodeHistory.index');
        });
    });
    Route::group(['prefix' => 'gift-send'], function () {
        Route::group(['prefix' => 'gift-send-setting'], function () {
            Route::get('/', 'GiftSendController@index')->name('admin.giftSend.index');
            Route::post('/edit', 'GiftSendController@update')->name('admin.giftSend.update');
        });

        Route::group(['prefix' => 'gift-send-history'], function () {
            Route::get('/', 'GiftSendHistoryController@index')->name('admin.giftSendHistory.index');
            Route::post('/getUserVip', 'GiftSendHistoryController@getUserVip')->name('admin.giftSendHistory.getUserVip');
            Route::post('/getUserVip2', 'GiftSendHistoryController@getUserVip2')->name('admin.giftSendHistory.getUserVip2');
            Route::get('/addLuckyIndex', 'GiftSendHistoryController@addLuckyIndex')->name('admin.giftSendHistory.addLuckyIndex');
            Route::post('/addLuckyIndex', 'GiftSendHistoryController@postAddLuckyIndex')->name('admin.giftSendHistory.postAddLuckyIndex');
            Route::get('/addGiftForUser', 'GiftSendHistoryController@addGiftForUser')->name('admin.giftSendHistory.addGiftForUser');
            Route::get('/addAccuForUser', 'GiftSendHistoryController@addAccuForUser')->name('admin.giftSendHistory.addAccuForUser');
            Route::post('/addGiftForUser', 'GiftSendHistoryController@postAddGiftForUser')->name('admin.giftSendHistory.postAddGiftForUser');
            Route::post('/addAccuForUser', 'GiftSendHistoryController@postAddAccuForUser')->name('admin.giftSendHistory.postAddAccuForUser');
            Route::get('/addGiftVip', 'GiftSendHistoryController@addGiftVip')->name('admin.giftSendHistory.addGiftVip');


            Route::get('send/{id}/{status}','GiftSendHistoryController@send')->name('admin.giftSendHistory.send');
            Route::get('send/{id}/{status}/{luckyNumber?}','GiftSendHistoryController@send')->name('admin.giftSendHistory.send');
        });
        Route::group(['prefix' => 'lucky-history'], function () {
            Route::get('/luckyHistory', 'GiftSendHistoryController@luckyHistory')->name('admin.giftSendHistory.luckyHistory');
        });
    });
    //    Route::group(['prefix' => 'transaction-history'],function (){
    //    });
    Route::group(['prefix' => 'blog-list'], function () {
        Route::group(['prefix' => 'category-blog'], function () {
            Route::get('/', 'CategoryBlogController@index')->name('admin.cateBlog.index');
            Route::get('/add', 'CategoryBlogController@add')->name('admin.cateBlog.add');
            Route::post('/add', 'CategoryBlogController@store')->name('admin.cateBlog.store');
            Route::get('/edit/{id}', 'CategoryBlogController@edit')->name('admin.cateBlog.edit');
            Route::post('/edit/{id}', 'CategoryBlogController@update')->name('admin.cateBlog.update');
            Route::get('/delete/{id}', 'CategoryBlogController@delete')->name('admin.cateBlog.delete');
            Route::get('/status/{id}/{status}', 'CategoryBlogController@status')->name('admin.cateBlog.status');
        });

        Route::group(['prefix' => 'blog'], function () {
            Route::get('/', 'BlogController@index')->name('admin.blogs.index');
            Route::get('/add', 'BlogController@add')->name('admin.blogs.add');
            Route::post('/add', 'BlogController@store');
            Route::get('/edit/{id}', 'BlogController@edit')->name('admin.blogs.edit');
            Route::post('/edit/{id}', 'BlogController@update');
            Route::get('/delete/{id}', 'BlogController@delete')->name('admin.blogs.delete');
            Route::get('/status/{id}/{status}', 'BlogController@status')->name('admin.blogs.status');
            Route::get('/hot/{id}/{hot}', 'BlogController@hot')->name('admin.blogs.hot');
        });
    });
    Route::group(['prefix' => 'help-list'], function () {
        Route::group(['prefix' => 'category-help'], function () {
            Route::get('/', 'CategoryHelpController@index')->name('admin.cateHelp.index');
            Route::get('/add', 'CategoryHelpController@add')->name('admin.cateHelp.add');
            Route::post('/add', 'CategoryHelpController@store')->name('admin.cateHelp.store');
            Route::get('/edit/{id}', 'CategoryHelpController@edit')->name('admin.cateHelp.edit');
            Route::post('/edit/{id}', 'CategoryHelpController@update')->name('admin.cateHelp.update');
            Route::get('/delete/{id}', 'CategoryHelpController@delete')->name('admin.cateHelp.delete');
            Route::get('/status/{id}/{status}', 'CategoryHelpController@status')->name('admin.cateHelp.status');
        });

        Route::group(['prefix' => 'help'], function () {
            Route::get('/', 'HelpController@index')->name('admin.help.index');
            Route::get('/add', 'HelpController@add')->name('admin.help.add');
            Route::post('/add', 'HelpController@store');
            Route::get('/edit/{id}', 'HelpController@edit')->name('admin.help.edit');
            Route::post('/edit/{id}', 'HelpController@update');
            Route::get('/delete/{id}', 'HelpController@delete')->name('admin.help.delete');
            Route::get('/status/{id}/{status}', 'HelpController@status')->name('admin.help.status');
        });
    });
    Route::group(['prefix' => 'client-says'], function () {
        Route::get('/', 'ClientSaysController@index')->name('admin.clientSays.index');
        Route::get('/add', 'ClientSaysController@add')->name('admin.clientSays.add');
        Route::post('/add', 'ClientSaysController@store');
        Route::get('/edit/{id}', 'ClientSaysController@edit')->name('admin.clientSays.edit');
        Route::post('/edit/{id}', 'ClientSaysController@update');
        Route::get('/delete/{id}', 'ClientSaysController@delete')->name('admin.clientSays.delete');
        Route::get('/status/{id}/{status}', 'ClientSaysController@status')->name('admin.clientSays.status');
    });

    Route::group(['prefix' => 'contact'], function () {
        Route::get('/', 'ContactController@index')->name('admin.contact.index');
        Route::post('/dislay', 'ContactController@dislay')->name('admin.contact.dislay');
        Route::get('/delete/{id}', 'ContactController@delete')->name('admin.contact.delete');
        Route::get('/status/{id}/{status}', 'ContactController@status')->name('admin.contact.status');
    });
    Route::get('/setting', 'SettingsController@index')->name('admin.settings.index');
    Route::post('/setting-update', 'SettingsController@update')->name('admin.settings.index.update');

    Route::get('/', 'TransactionHistoryController@index')->name('admin.transactionHistory.index');
    Route::get('/add', 'TransactionHistoryController@add')->name('admin.transactionHistory.add');
    Route::post('/add', 'TransactionHistoryController@store')->name('admin.transactionHistory.store');
    Route::get('/edit', 'TransactionHistoryController@edit')->name('admin.transactionHistory.edit');
    Route::post('/edit', 'TransactionHistoryController@storeMinus')->name('admin.transactionHistory.storeMinus');

    Route::group(['prefix' => 'relog-momo'], function () {
        Route::get('/', 'RelogMomoController@index')->name('admin.relogMomo.index');
        Route::get('/check', 'RelogMomoController@check')->name('admin.relogMomo.check');
        Route::get('/login', 'RelogMomoController@login')->name('admin.relogMomo.login');
        Route::post('/otp', 'RelogMomoController@otp')->name('admin.relogMomo.otp');
    });
});
Route::group(['namespace' => 'Frontend'], function () {
    //Route::get('/', 'IndexController@index')->name('frontend.index');
	/*Route::get('/', function(){
		return 'Sever đang bảo trì, vui lòng quay lại sau';
	});*/
    Route::post('/webhook/casso', [CassoWebhookController::class, 'handle']);
    Route::get('/get-coin-balance', [UserController::class, 'getCoinBalance'])->middleware('auth');

 
    Route::get('/get-user-info', function () {
        return Auth::user()->only(['coin']);
    })->middleware('auth');

    Route::get('/get-current-coins', 'UserController@getCurrentCoins')->middleware('auth');

    Route::post('/generate-qr-code', [QRCodeController::class, 'generateQRCode'])->name('generate.qr.code');

    Route::get('/pusher-config', [PusherConfigController::class, 'getConfig']);

    Route::get('/quick-buy', [ProductController::class, 'quickBuy'])->name('frontend.quickBuy');
    Route::post('/buy-now', [ProductController::class, 'buyNow'])->name('frontend.buyNow');
 
    Route::get('/user-info', 'UserInfoController@index')->name('frontend.user');
    Route::post('/user-info/un', 'UserInfoController@unAcc')->name('frontend.unAcc');
    Route::post('/user-info/select-char', 'UserInfoController@setCharacter')->name('frontend.selectChar');
    Route::post('/user-info/claim-gift', 'UserInfoController@claimGift')->name('frontend.claimGift');	
    Route::get('/download', 'IndexController@download')->name('frontend.download');
    Route::get('/get-vip-info', 'UserInfoController@getVipInfoAjax')->name('frontend.getVipInfo');

    Route::get('/lucky-wheel', 'LuckyController@index')->name('frontend.lucky');
    Route::post('/lucky-product', 'LuckyController@luckyProduct')->name('frontend.luckyProduct');
    Route::get('/getHistoryRolanty', 'LuckyController@getHistoryRolanty')->name('frontend.getHistoryRolanty');

    Route::get('/history', 'IndexController@history')->name('frontend.history');
    Route::get('/introduce', 'IndexController@introduce')->name('frontend.introduce');
    Route::get('/topServer', 'IndexController@topServer')->name('frontend.topServer');
    Route::get('/rechargeCard', 'IndexController@rechargeCard')->name('frontend.rechargeCard');
    Route::post('/rechargeCardPost', 'IndexController@rechargeCardPost')->name('frontend.rechargeCardPost');
    Route::post('/giftCode', 'IndexController@giftCode')->name('frontend.giftCode');

    Route::get('/product_detail/{id}', 'ProductController@productDetail')->name('frontend.productDetail');
    Route::get('/product_list', 'ProductController@productCate')->name('frontend.productCate');
    Route::get('/addCart/{id}', 'ProductController@addCart')->name('frontend.cart.add');
    Route::post('/buyAccumulator', 'ProductController@buyAccumulator')->name('frontend.buyAccumulator');
    Route::get('/delete/{id}', 'ProductController@delete')->name('frontend.cart.delete');
    Route::get('/decrease/{id}', 'ProductController@decrease')->name('frontend.cart.decrease');
    Route::get('/decrease/{id}', 'ProductController@decrease')->name('frontend.cart.decrease');
    Route::get('product_convince', 'ProductController@convince')->name('frontend.cart.convince');
    Route::get('/increment/{id}', 'ProductController@increment')->name('frontend.cart.increment');
    Route::get('/order', 'ProductController@order')->name('frontend.cart.order');
    Route::get('/payment', 'ProductController@payment')->name('frontend.cart.payment');

    Route::get('/search', 'ProductController@search')->name('frontend.search');

    Route::get('/blog-cate/{id}', 'BlogController@blogCate')->name('frontend.blogCate');
    Route::get('/blog-detail/{slug}', 'BlogController@blogDetail')->name('frontend.blogDetail');
    Route::get('/blog-list', 'BlogController@blogList')->name('frontend.blogList');
    Route::get('/help-cate/{id}', 'HelpController@helpCate')->name('frontend.helpCate');
    Route::get('/help-detail/{id}', 'HelpController@helpDetail')->name('frontend.helpDetail');

    Route::get('getSendMail', 'PasswordResetController@getSendMail')->name('getSendMail');
    Route::post('postSendMail', 'PasswordResetController@create')->name('postSendMail');

    Route::get('/password/find/{token}', 'PasswordResetController@find');

    Route::get('backResetPass', 'UserController@backResetPass')->name('backResetPass');
    Route::get('putResetPass', 'UserController@putResetPass')->name('putResetPass');
    Route::post('resetPass', 'UserController@resetPass')->name('resetPass2');

    Route::post('/password/reset', 'PasswordResetController@reset')->name('resetPass');
});

Route::post('register', 'Frontend\UserController@register')->name('register');
Route::post('login', 'Frontend\UserController@login')->name('login');
Route::post('loginHome', 'Frontend\UserController@loginHome')->name('loginHome');

Route::get('logout', 'Frontend\UserController@logout')->name('logout');

Broadcast::routes(['middleware' => ['auth:api']]);  // Thay đổi từ auth:sanctum thành auth:api

Route::get('/{path?}', function () {
    return view('kytrancac');
})->where('path', '.*');



