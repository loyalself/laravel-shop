<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Yansongda\Pay\Pay;
/**
 * 容器是现代 PHP 开发的一个重要概念，Laravel 就是在容器的基础上构建的。
 * 我们将支付操作类实例注入到容器中，在以后的代码里就可以直接通过 app('alipay') 来取得对应的实例，而不需要每次都重新创建。
   我们通常在 AppServiceProvider 的 register() 方法中往容器中注入实例
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * 1.$this->app->singleton() 往服务容器中注入一个单例对象，第一次从容器中取对象时会调用回调函数来生成对应的对象并保存到容器中，
     *   之后再去取的时候直接将容器中的对象返回。
     *
     * 2.app()->environment() 获取当前运行的环境，线上环境会返回 production。
     *   对于支付宝，如果项目运行环境不是线上环境，则启用开发模式，并且将日志级别设置为 DEBUG。
     *   由于微信支付没有开发模式，所以仅仅将日志级别设置为 DEBUG
     */
    public function register(){
        // 往服务容器中注入一个名为 alipay 的单例对象
        $this->app->singleton('alipay', function () {
            $config = config('pay.alipay');
            $config['notify_url'] = route('payment.alipay.notify'); //后端回调
            $config['return_url'] = route('payment.alipay.return'); //前端回调
            // 判断当前项目运行环境是否为线上环境
            if (app()->environment() !== 'production') {
                $config['mode']         = 'dev';
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个支付宝支付对象
            return Pay::alipay($config);
        });

        $this->app->singleton('wechat_pay', function () {
            $config = config('pay.wechat');
            if (app()->environment() !== 'production') {
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个微信支付对象
            return Pay::wechat($config);
        });
    }
}
