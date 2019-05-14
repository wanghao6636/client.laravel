<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    //对称加密
    public function test()
    {
        $data=[
            'clickname'=>'张浩维',
            'email'=>'99999@qq.com',
            'age'=>'4',
            'bank_id'=>'11111111111'
        ];
        $method='AES-256-CBC';
        $pass='qweasd';
        $iv='12345q123456q123';
        //加密数据
        $json_str=json_encode($data);
        $send_data=base64_encode(openssl_encrypt($json_str,$method,$pass,OPENSSL_RAW_DATA,$iv));
        //echo 11;exit;
        echo $send_data;
        //curl发送数据
        $url='http://www.lument.com/test';
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$send_data);
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
                'Content-type:text/plain'
            ]);
        $resopt=curl_exec($ch);
        //监控错误码
        $err_code=curl_errno($ch);
        if($err_code>0){
            echo "CURL 错误码:".$err_code;exit;
        }
        curl_close($ch);

    }

    //非对称加密
    public function innt()
    {
        $data=[
            'nickname'=>'张浩维',
            'email'=>'978513@qq.com',
            'age'=>11
        ];
        //加密数据
        $ba64=json_encode($data);
        $o=openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
        openssl_private_encrypt($ba64,$deco,$o);
        $url="http://www.lument.com/innt";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$deco);
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
            'Content-type:text/plain'
        ]);
        $resop=curl_exec($ch);
        //监控错误码
        $err_code=curl_errno($ch);
        if($err_code>0){
            echo "CURL 错误码：".$err_code;die;
        }
        curl_close($ch);

    }

    //验签
    public function tens()
    {

        $data=[
            'email'=>'15235@qq.com',
            'admin'=>'admin',
            'sex'=>1
        ];
        $json_str=json_encode($data);
        //var_dump($json_str);
        $qq=openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
        //计算签名
        openssl_sign($json_str,$ufo,$qq);
        $bas64=base64_encode($ufo);
        //var_dump($bas64);
        $url="http://www.lument.com/sign?sign=".urlencode($bas64);
        var_dump($url);
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$json_str);
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
            'Content-type:text/plain'
        ]);
        //监控验证码
        $err_code=curl_errno($ch);

        if($err_code>0){
            echo "CURL 错误码：".$err_code;die;
        }
        curl_close($ch);
    }
}
