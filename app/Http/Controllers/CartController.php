<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
   /* //添加商品到购物车
    public function add(AddCartRequest $request){
        $user   = $request->user();
        $skuId  = $request->input('sku_id');
        $amount = $request->input('amount');

        // 从数据库中查询该商品是否已经在购物车中
        if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            // 如果存在则直接叠加商品数量
            $cart->update([
                'amount' => $cart->amount + $amount,
            ]);
        } else {
            // 否则创建一个新的购物车记录
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }
        return [];
    }

    //购物车列表
    public function index(Request $request){
        //with(['productSku.product']) 方法用来预加载购物车里的商品和 SKU 信息
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();
        //通常来说用户重复使用最近用过的地址概率比较大，因此我们在取地址的时候根据 last_used_at 最后一次使用时间倒序排序，这样用户体验会好一些
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();
        return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
    }

    public function remove(ProductSku $sku, Request $request){
        $request->user()->cartItems()->where('product_sku_id', $sku->id)->delete();
        return [];
    }*/

    //优化:

    protected $cartService;

    // 利用 Laravel 的自动解析功能注入 CartService 类
    public function __construct(CartService $cartService){
        $this->cartService = $cartService;
    }

    public function index(Request $request){
        $cartItems = $this->cartService->get();
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();
        return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
    }

    public function add(AddCartRequest $request){
        $this->cartService->add($request->input('sku_id'), $request->input('amount'));
        return [];
    }

    public function remove(ProductSku $sku, Request $request){
        $this->cartService->remove($sku->id);
        return [];
    }
}
