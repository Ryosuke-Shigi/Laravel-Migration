<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;    //追加：２０２１１０１２

class free_specialized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /*
    *   Request→Route→middleware→controller→view→middleware→response
    *
    *
    */
    public function handle($request, Closure $next)
    {
        //$response=$next($request);    //afterミドルウェアで使用する
        //フリーチケットであれば
        if($request->tickets_kind==1){
            //いったん空白
            dump("フリー");

        }else{
            dump("指定チケット");
        }

        return $next($request);//リクエストを次のステップに進める　リクエストがコントローラーに渡される

        //return $response;//afterミドルウェア時に使用
    }
}
