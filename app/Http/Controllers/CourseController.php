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

class CourseController extends Controller
{
    public function applyCourse(Request $request)
    {
        $res_data = [];
        if ($request->get('ukey')) {
            $res = DB::table('user')->where('u_key', $request->get('ukey'))->first();
            if ($res->u_type == 2) {
                $res_data['errcode'] = 2;
                $res_data['errmsg'] = '您是普通用户，请先申请成为管理员';
            } elseif ($res->u_type == 1) {
                $course_num = DB::table('course')->where('u_id', $res->id)->where('c_state', 1)->count();
                if ($res->u_max_course <= $course_num) {
                    $res_data['errcode'] = 3;
                    $res_data['errmsg'] = '您已达到最大课程量(' . $res->u_max_course . ')，请删减课程或者联系客服增加课程量';
                }
            }
        } else {
            $res_data['errcode'] = 1;
            $res_data['errmsg'] = '用户标识错误';
        }
        if (!$res_data) {
            $file_name = md5(time()) . '.jpg';
            $request->file('cover')->move('cover', $file_name);
            $sql_data = [
                'u_id' => $res->id,
                'c_name' => $request->get('name'),
                'c_intro' => $request->get('intro'),
                'c_cover' => 'cover/' . $file_name,
                'c_form_id' => $request->get('form_id')
            ];
            $r = DB::table('course')->insert($sql_data);
            if ($r) {
                $res_data['errcode'] = 0;
                $res_data['errmsg'] = '提交成功，请耐心等待系统管理员审核';
            } else {
                $res_data['errcode'] = 4;
                $res_data['errmsg'] = '网络繁忙，请稍后再试';
            }
        }
        return response()->json($res_data);
    }

    public function loadCourses(Request $request){
        $res = DB::table('course')->select('id', 'u_id', 'c_name', 'c_intro', 'c_cover')->where('c_state', 1)->orderBy('id', 'desc')->offset($request->get('start'))->limit($request->get('count'))->get();
        foreach ( $res as $k=>$v ){
            $res[$k]->c_cover = env('APP_URL').$res[$k]->c_cover;
        }
        return response()->json($res);
    }
}