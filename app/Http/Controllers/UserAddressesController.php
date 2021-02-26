<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    //收货地址列表
    public function index(Request $request){
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }


}
