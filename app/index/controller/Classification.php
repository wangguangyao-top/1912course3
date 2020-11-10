<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\ClassificationModel;

class Classification extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $cla_info=ClassificationModel::where(["is_del"=>1])->select();
        return view("index@classification/index",compact("cla_info"));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view("index@classification/create");
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $cla_name=$request->post("cla_name");
        $data=[
            "cla_name"=>$cla_name,
            "add_time"=>time()
        ];
        $res=ClassificationModel::insert($data);
        if($res){
            echo  json_encode(["code"=>"200","msg"=>"添加成功"]);die;
        }else{
            echo json_encode(["code"=>"1","msg"=>"添加失败"]);die;
        }
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
    public function edit(Request $request)
    {
        $cla_id=$request->get("cla_id");
        $edit_info=ClassificationModel::find($cla_id);
        return view("index@classification/edit",compact("edit_info"));
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $cla_id=$request->post("cla_id");
        $cla_name=$request->post("cla_name");
        $data=[
            "cla_name"=>$cla_name
        ];
        $res=ClassificationModel::where(["cla_id"=>$cla_id])->update($data);
        if($res){
            echo json_encode(["code"=>200,"msg"=>"修改成功"]);die;
        }else{
            echo json_encode(["code"=>1,"msg"=>"修改失败"]);die;
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $cla_id=$request->post("cla_id");
        $res=ClassificationModel::where(["cla_id"=>$cla_id])->update(["is_del"=>2]);
        if($res){
            echo  json_encode(["code"=>"200","msg"=>"删除成功"]);die;
        }else{
            echo  json_encode(["code"=>"1","msg"=>"删除成功"]);die;
        }
    }
}
