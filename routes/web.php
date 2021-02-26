<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'PagesController@root')->name('root');
Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    //需要把这个路由放在 auth 这个中间件的路由组里面，因为只有已经登录的用户才能看到这个提示界面
    Route::get('/email_verify_notice', 'PagesController@emailVerifyNotice')->name('email_verify_notice'); //邮箱验证提醒
    Route::get('/email_verification/verify', 'EmailVerificationController@verify')->name('email_verification.verify'); //邮箱验证
    Route::get('/email_verification/send', 'EmailVerificationController@send')->name('email_verification.send'); //手动发送邮件

    // 开始 -- 检测邮箱是否验证
    Route::group(['middleware' => 'email_verified'], function() {
        Route::get('/test', function() {
            return 'Your email is verified';
        });
    });
    // 结束
});