<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\RbacUser;
use think\Db;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data=RbacUser::select();
//        $this->assign('data',$data);
        return view("index@user/index",['data'=>$data]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        if(Request()->isAjax()){
            $admin_name=$request->post("admin_name");
            $pwd=$request->post('pwd');
            $data=[
                'admin_name'=>$admin_name,
                'password'=>$pwd,
                'reg_time'=>time(),
            ];

            $res=RbacUser::insert($data);

            if($res){
                echo json_encode(['error'=>200,'msg'=>'OK']);
                die;
            }else{
                echo json_encode(['error'=>100001,'msg'=>'NO']);
                die;
            }
        }
        return view("index@user/create");
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        return view("index@user/edit");
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
