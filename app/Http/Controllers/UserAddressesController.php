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
}
