<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\RbacUser;

class Login extends Controller
{
    public function login(){
        return view("index@login/login");
    }
    public function do_login(){
        $request=Request();
        $admin_name=$request->post("admin_name");
        $password=$request->post("password");
        $admin_info=RbacUser::where(["admin_name"=>$admin_name])->find();
        if($admin_info){
            if($admin_info["password"]==$password){
                echo json_encode(["code"=>200,"msg"=>"登陆成功"]);die;
            }else{
                echo json_encode(["code"=>1,"msg"=>"登陆失败"]);die;
            }
        }else{
            echo json_encode(["code"=>1,"msg"=>"没有此用户"]);die;
        }
    }
}
