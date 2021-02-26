<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

/**
 * 无效请求异常类
 */
class InvalidRequestException extends Exception
{
    public function __construct(string $message = "", int $code = 400){
        parent::__construct($message, $code);
    }

    /**
     * 异常信息渲染页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render(Request $request){
        if ($request->expectsJson()) {//如果是 ajax 请求则返回错误 JSON 格式的数据
            // json() 方法第二个参数就是 Http 返回码
            return response()->json(['msg' => $this->message], $this->code);
        }
        return view('pages.error', ['msg' => $this->message]);
    }
}
