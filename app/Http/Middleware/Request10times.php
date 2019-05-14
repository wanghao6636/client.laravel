<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;
use Closure;

class Request10times
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key='request10times';
        $num=Redis::get($key);
//        if($num>10){
//            die('限制次数');
//        }
        if($num>10){
            $response=[
                'error'=>9008,
                'msg'=>'限制次数'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        Redis::incr($key);
        Redis::expire($key,5);
        echo $num;echo '<br>';
        echo date('Y-m-d H:i:s');
        return $next($request);
    }
}
