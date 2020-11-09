<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\ClassificationModel;
use app\index\model\QuestionModel;

class Question extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $where=[
            "course_question_bank.is_del"=>1
        ];
        $bank_info=QuestionModel::
            leftjoin("course_question_classification","course_question_bank.cla_id=course_question_classification.cla_id")
            ->where($where)
            ->select();

        return view("index@question/index",compact("bank_info"));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $cla_info=ClassificationModel::select();
        return view("index@question/create",compact("cla_info"));
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $bank_title=$request->post("bank_title");
        $bank_type=$request->post("bank_type");
        $cla_id=$request->post("cla_id");
        $bank_content=$request->post("bank_content");
        $bank_data=[
            "bank_title"=>$bank_title,
            "bank_type"=>$bank_type,
            "cla_id"=>$cla_id,
            "bank_content"=>$bank_content,
            "add_time"=>time(),
        ];
        $res=QuestionModel::insert($bank_data);
        if($res){
            echo json_encode(["code"=>200,"msg"=>"添加成功"]);die;
        }else{
            echo json_encode(["code"=>1,"msg"=>"添加失败"]);die;
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
    public function edit($bank_id)
    {
        $cla_info=ClassificationModel::select();
        $edit_info=QuestionModel::find($bank_id);
        return view("index@question/edit",compact("edit_info","cla_info"));
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
        $bank_id=$request->post("bank_id");
        $bank_title=$request->post("bank_title");
        $bank_type=$request->post("bank_type");
        $cla_id=$request->post("cla_id");
        $bank_content=$request->post("bank_content");
        $bank_data=[
            "bank_title"=>$bank_title,
            "bank_type"=>$bank_type,
            "cla_id"=>$cla_id,
            "bank_content"=>$bank_content,
            "add_time"=>time(),
        ];
        $res=QuestionModel::where(["bank_id"=>$bank_id])->update($bank_data);
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
        $bank_id=$request->post("bank_id");
        $res=QuestionModel::where(["bank_id"=>$bank_id])->update(["is_del"=>2]);
        if($res){
            echo json_encode(["code"=>200,"msg"=>"删除成功"]);die;
        }else{
            echo json_encode(["code"=>1,"msg"=>"删除失败"]);die;
        }
    }
}
