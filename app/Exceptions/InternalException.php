<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

/**
 * 系统异常:
 *  比如连接数据库失败，对于此类异常我们需要有限度地告知用户发生了什么，但又不能把所有信息都暴露给用户（比如连接数据库失败的信息里会包含数据库地址和账号密码）;
 *  因此我们需要传入两条信息，一条是给用户看的，另一条是打印到日志中给开发人员看的
 */
class InternalException extends Exception
{
    /**
     * 返回给用户的错误信息提示
     * @var string
     */
    protected $msgForUser;

    /**
     * 这个异常的构造函数第一个参数就是原本应该有的异常信息比如连接数据库失败，第二个参数是展示给用户的信息，通常来说只需要告诉用户 系统内部错误 即可，
     * 因为不管是连接 Mysql 失败还是连接 Redis 失败对用户来说都是一样的，就是系统不可用，用户也不可能根据这个信息来解决什么问题
     * @param string $message
     * @param string $msgForUser
     * @param int $code
     */
    public function __construct(string $message, string $msgForUser = '系统内部错误', int $code = 500){
        parent::__construct($message, $code);
        $this->msgForUser = $msgForUser;
    }

    public function render(Request $request){
        if ($request->expectsJson()) {
            return response()->json(['msg' => $this->msgForUser], $this->code);
        }
        return view('pages.error', ['msg' => $this->msgForUser]);
    }
}
