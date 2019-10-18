<?php
/**
 * Created by PhpStorm.
 * User: uriel
 * Date: 2018/7/8 0008
 * Time: 13:54
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $res_data = [];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . env('APP_ID', '') . "&secret=" . env('APP_SECRET', '') . "&js_code=" . $request->get('code') . "&grant_type=authorization_code";
        $res = json_decode(file_get_contents($url), true);
        if (array_key_exists('session_key', $res) and sha1($request->get('rawData') . $res['session_key']) == $request->get('signature')) {
            $aesKey = base64_decode($res['session_key']);
            $aesIV = base64_decode($request->get('iv'));
            $aesCipher = base64_decode($request->get('encryptedData'));
            $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
            $data = json_decode($result, true);
            $u_key = sha1('kaikai' . $res['session_key']);
            $avatar = file_get_contents($data['avatarUrl']);
            $wx_file_name = 'avatars/wx_'.md5($data['openId']).'.jpg';
            file_put_contents($wx_file_name,$avatar);
            $sql_data = [
                'wx_name' => $data['nickName'],
                'wx_avatar' => $wx_file_name,
                'wx_gender' => $data['gender'],
                'wx_address' => $data['country'] . ':' . $data['province'] . ':' . $data['city'],
                'wx_session_key' => $res['session_key'],
                'u_key' => $u_key
            ];
            $user = DB::table('user')->where('wx_id', $data['openId'])->first();
            if ($user) {
                $userInfo = [
                    'nick_name'=>$user->u_name,
                    'avatar'=>env('APP_URL') . $user->u_avatar
                ];
                $r = DB::table('user')->where('id', $user->id)->update($sql_data);
            } else {
                $u_file_name = 'avatars/u_'.md5($data['openId']).'.jpg';
                file_put_contents($u_file_name,$avatar);
                $userInfo = [
                    'nick_name'=>$data['nickName'],
                    'avatar'=>env('APP_URL') . $request->url() . $u_file_name
                ];
                $sql_data['wx_id'] = $data['openId'];
                $sql_data['u_name'] = $data['nickName'];
                $sql_data['u_avatar'] = $u_file_name;
                $r = DB::table('user')->insert($sql_data);
            }
            if($user or $r){
                $res_data['result'] = 0;
                $res_data['userInfo'] = $userInfo;
                $res_data['skey'] = $u_key;
            }else{
                $res_data['result'] = 1;
                $res_data['errmsg'] = '登录初始化失败！';
            }
        }else{
            $res_data['result'] = 1;
            $res_data['errmsg'] = '登录信息错误！';
        }
        return response()->json($res_data);
    }


    public function setName(Request $request){
        $res_data = [];
        if($request->get('name') and $request->get('ukey')){
            if(strlen($request->get('name')) <= 15){
                $res = DB::table('user')->where('u_key', $request->get('ukey'))->update(['u_name'=>$request->get('name')]);
                $res_data['errcode'] = 0;
                $res_data['errmsg'] = '设置成功';
            }else{
                $res_data['errcode'] = 1;
                $res_data['errmsg'] = '昵称不能超过15个字符';
            }
        }else{
            $res_data['errcode'] = 2;
            $res_data['errmsg'] = '不能为空';
        }
        return response()->json($res_data);
    }

    public function setAvatar(Request $request){
        $res_data = [];
        if($request->hasFile('avatar') and $request->get('ukey')){
            $res = DB::table('user')->where('u_key', $request->get('ukey'))->first();
            $u_file_name = 'u_'.md5($res->wx_id).'.jpg';
            $request->file('avatar')->move('avatars', $u_file_name);
            $res_data['errcode'] = 0;
            $res_data['errmsg'] = '设置成功';
        }else{
            $res_data['errcode'] = 1;
            $res_data['errmsg'] = '不能为空';
        }
        return response()->json($res_data);
    }

    public function getUserType(Request $request){
        $res_data = [];
        if($request->get('ukey')){
            $type = DB::table('user')->where('u_key', $request->get('ukey'))->value('u_type');
            $res_data['errcode'] = 0;
            $res_data['errmsg'] = $type;
        }else{
            $res_data['errcode'] = 1;
            $res_data['errmsg'] = '用户验证错误';
        }
        return response()->json($res_data);
    }

    public function getCanApply(Request $request){
        $res_data = [];
        if($request->get('ukey')){
            $type = DB::table('user')->where('u_key', $request->get('ukey'))->value('u_form_id');
            $res_data['errcode'] = 0;
            $res_data['errmsg'] = $type=='';
        }else{
            $res_data['errcode'] = 1;
            $res_data['errmsg'] = '用户验证错误';
        }
        return response()->json($res_data);
    }


    public function applyAdmin(Request $request){
        $res_data = [];
        if($request->get('ukey') and $request->get('class_type') and $request->get('intro') and $request->get('form_id')){
            $res = DB::table('user')->where('u_key', $request->get('ukey'))->update(['u_class_type'=>$request->get('class_type'),'u_intro'=>$request->get('intro'),'u_form_id'=>$request->get('form_id')]);
            $res_data['errcode'] = 0;
            $res_data['errmsg'] = '提交成功';
        }else{
            $res_data['errcode'] = 1;
            $res_data['errmsg'] = '数据不能为空';
        }
        return response()->json($res_data);
    }
}