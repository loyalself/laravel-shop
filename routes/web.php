<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'PagesController@root')->name('root');
Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    //需要把这个路由放在 auth 这个中间件的路由组里面，因为只有已经登录的用户才能看到这个提示界面
    Route::get('/email_verify_notice', 'PagesController@emailVerifyNotice')->name('email_verify_notice');

    // 开始
    Route::group(['middleware' => 'email_verified'], function() {
        Route::get('/test', function() {
            return 'Your email is verified';
        });
    });
    // 结束
});