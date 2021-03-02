<?php
use Illuminate\Support\Facades\Route;

Route::get('ss','ProductsController@ss');

//Route::get('/', 'PagesController@root')->name('root');
Auth::routes();

Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index'); //首页
//Route::get('products/{product}', 'ProductsController@show')->name('products.show'); //商品详情页

Route::group(['middleware' => 'auth'], function() {
    //需要把这个路由放在 auth 这个中间件的路由组里面，因为只有已经登录的用户才能看到这个提示界面
    Route::get('/email_verify_notice', 'PagesController@emailVerifyNotice')->name('email_verify_notice'); //邮箱验证提醒
    Route::get('/email_verification/verify', 'EmailVerificationController@verify')->name('email_verification.verify'); //邮箱验证
    Route::get('/email_verification/send', 'EmailVerificationController@send')->name('email_verification.send'); //手动发送邮件

    Route::group(['middleware' => 'email_verified'], function() {
        //Route::get('/test', function() { return 'Your email is verified';});
        Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index'); //收货地址列表页面
        Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create'); //新建收货地址页面
        Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store'); //新建收货地址
        Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit'); //编辑收货地址页面
        Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update'); //编辑收货地址逻辑
        Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy'); //删除收货地址
        Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');          //收藏商品
        Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');  //取消收藏
        Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites'); //收藏列表
        Route::post('cart', 'CartController@add')->name('cart.add'); //添加商品到购物车
        Route::get('cart', 'CartController@index')->name('cart.index');  //购物车列表
        Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');  //从购物车中移除商品
        Route::post('orders', 'OrdersController@store')->name('orders.store'); //下单
        Route::get('orders', 'OrdersController@index')->name('orders.index'); //订单列表
        Route::get('orders/{order}', 'OrdersController@show')->name('orders.show'); //订单详情
        Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay'); //唤起支付宝支付
        Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return'); //前端回调
    });
});

//移到这里为了解决与 用户收藏列表路由冲突
Route::get('products/{product}', 'ProductsController@show')->name('products.show'); //商品详情页

Route::get('alipay', function() {
    return app('alipay')->web([
        'out_trade_no' => time(),
        'total_amount' => '1',
        'subject'      => 'test subject - 测试',
    ]);
});

Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');//服务器端回调