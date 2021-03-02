<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
//创建一个订单支付成功的事件
//事件本身不需要有逻辑，只需要包含相关的信息即可，在我们这个场景里就只需要一个订单对象
class OrderPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $order;

    public function __construct(Order $order){
        $this->order = $order;
    }

    public function getOrder(){
        return $this->order;
    }
}
