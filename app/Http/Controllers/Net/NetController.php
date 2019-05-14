<?php

namespace App\Http\Controllers\Net;
use App\Model\NetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;

class NetController extends Controller
{
    //
    public function regis()
    {
        //header("Access-Control-Allow-Origin:http://www.laravel.com");
        return view('net/net');
    }

    public function neet(Request $request)
    {
        $email=$request->input('email');
        $pass=$request->input('pass');
        $pass2=$request->input('pass2');
        if($pass!==$pass2){
            $response=[
                'error'=>50002,
                'msg'=>'两次密码输入不一致'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //数据库中取
        $regi=NetModel::where(['email'=>$email])->first();
        //var_dump($regi);
        //判断邮箱
        if($regi){
            $response=[
                'error'=>60003,
                'msg'=>'邮箱不存在'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $data=[
            'name'=>$request->input('name'),
            'email'=>$email,
            'pass'=>$pass
        ];
        //存入数据库
        $ou=NetModel::insertGetId($data);
        if($ou){
            $response=[
                'error'=>60002,
                'msg'=>'注册成功'
            ];

        }else{
            $response=[
                'error'=>6001,
                'msg'=>'注册失败'
            ];
        }
        header('Refresh:3:url=http://www.laravel.com/logins');
        return(json_encode($response,JSON_UNESCAPED_UNICODE));
    }
    //5.13模拟登录
    public function logins()
    {
        return view('login.login');
    }
    public function login(Request $request)
    {
        $email=$request->input('email');
        $pass=$request->input('pass');
        $uu=NetModel::where(['email'=>$email])->first();
        //var_dump($uu);
        if($uu){
            if(password_verify($pass,$uu->pass)){
                $token=$this->logintoken($uu->id);
                $redis_token_key='acccess:id'.$uu->id;
                Redis::set($redis_token_key,$token);
                Redis::expire($redis_token_key,604800);

                //生成token
                $response=[
                    'error'=>0,
                    'msg'=>'OK',
                    'data'=>[
                        'token'=>$token,
                    ],
                ];
            }else{
                $response=[
                    'error'=>50014,
                    'msg'=>'密码不正确',
                ];
            }
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }else{
            //用户存在不存在
            $response=[
                'error'=>50019,
                'msg'=>'用户不存在',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }
    //生成登录token
    public function logintoken($id)
    {
        return substr(sha1($id.time().Str::random(10)),5,15);
    }


}
