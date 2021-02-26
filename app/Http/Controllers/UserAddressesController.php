<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    //收货地址列表
    public function index(Request $request){
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    //创建收货地址页面
    public function create(){
        return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
    }

    //创建收货地址逻辑
    public function store(UserAddressRequest $request){
        //通过模型关联的方法创建
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));
        return redirect()->route('user_addresses.index');
    }

    //编辑收货地址页面  注意:控制器的参数名 $user_address 必须和路由中的 {user_address} 一致才可以
    public function edit(UserAddress $user_address){
        $this->authorize('own', $user_address);
        return view('user_addresses.create_and_edit', ['address' => $user_address]);
    }

    //编辑收货地址逻辑
    public function update(UserAddress $user_address, UserAddressRequest $request){
        $this->authorize('own', $user_address);
        $user_address->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));
        return redirect()->route('user_addresses.index');
    }

    //删除收货地址
    public function destroy(UserAddress $user_address){
        $this->authorize('own', $user_address);
        $user_address->delete();
        // 因为是 ajax 请求,所以把之前的 redirect 改成返回空数组
        return [];
    }
}
