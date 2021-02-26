<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('users', 'UsersController@index');                  //注册用户列表
    $router->get('products', 'ProductsController@index');            //商品列表
    $router->get('products/create', 'ProductsController@create');    //创建商品页面
    $router->post('products', 'ProductsController@store');           //创建商品
    $router->get('products/{id}/edit', 'ProductsController@edit');   //编辑商品页面
    $router->put('products/{id}', 'ProductsController@update');      //更新商品
});
