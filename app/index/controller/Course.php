<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Course extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view("index@course/index");
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $CategoryModel=new CategoryModel;
        $data=$CategoryModel->select();
        $CateInfo=$this->CateInfo($data);
        $this->assign("CateInfo",$CateInfo);
        return view("course/create");
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
        return view("index@course/edit");
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
    //无限极分类
    function CateInfo($data,$pid=0,$level=1){
        static $info=[];
        foreach($data as $k=>$v){
            if($v['p_id']==$pid){
                $v['level']=$level;
                $info[]=$v;
                $this->CateInfo($data,$v['cate_id'],$v['level']+1);
            }
        }
        return $info;
    }
    /**失败*/
    function fail($font){
        $arr=["code"=>2,"font"=>$font];
        echo json_encode($arr);die;
    }
    /**成功*/
    function successly($font=''){
        $arr=["code"=>1,"font"=>$font];
        echo json_encode($arr);die;
    }
}
