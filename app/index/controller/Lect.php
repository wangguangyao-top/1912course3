<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\LectModel;
use app\index\model\Category;

class Lect extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //$course = Category::select();
        $data = LectModel::leftjoin('course_category','course_lect.cate_id=course_category.cate_id')->where('course_lect.is_del',1)->select();
        //dump($data);die;
        return view("index@lect/index",['data'=>$data]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $course = Category::select();

        return view("index@lect/create",['course'=>$course]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $lect_name = $request->post('lect_name');
        $cate_id = $request->post('cate_id');
        $lect_resume = $request->post('lect_resume');
        $lect_style = $request->post('lect_style');
        $data = [
            'lect_name'=>$lect_name,
            'cate_id'=>$cate_id,
            'lect_resume'=>$lect_resume,
            'lect_style'=>$lect_style,
            'add_time'=>time(),
        ];
        $img=Request()->file('lect_image');
        //文件上传
        if($_FILES['lect_image']['error']==0){
            $img=$this->uploads($img);
            $data['lect_image']=$img;
        }
        //文件上传
        //dump($data);die;
        $res = LectModel::insert($data);
        if($res){
            $this->success("添加成功","lect/index");
        }else{
            $this->error("添加失败","lect/create");
        }
    }

    public function uploads($files){
        //$files=request()->file($file);
        $info=$files->move('./uploads');
        if($info){
            return $info->getSaveName();
        }else{
            return false;
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
    public function edit($id)
    {
        $data = LectModel::where('lect_id',$id)->find();
        $cate = Category::select();
        return view("index@lect/edit",['data'=>$data,'cate'=>$cate]);
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
        $lect_name = $request->post('lect_name');
        $cate_id = $request->post('cate_id');
        $lect_resume = $request->post('lect_resume');
        $lect_style = $request->post('lect_style');
        $data = [
            'lect_name'=>$lect_name,
            'cate_id'=>$cate_id,
            'lect_resume'=>$lect_resume,
            'lect_style'=>$lect_style,
            'add_time'=>time(),
        ];
        $img=Request()->file('lect_image');
        if($_FILES['lect_image']['error']==0){
            $img=$this->uploads($img);
            $data['lect_image']=$img;
        }
        $res = LectModel::where('lect_id',$id)->update($data);
        //print_r($res);die;
        if($res){
            $this->success("修改成功","lect/index");
        }else{
            $this->error("修改失败","lect/update");
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $where=[
            ['lect_id','=',$id],//用户的id
            ['is_del','=',1]//没有被删除
        ];
        $res = LectModel::where($where)->update(['is_del'=>2]);
        if($res){
            $this->success("修改成功","lect/index");
        }else{
            $this->error("修改失败","lect/index");
        }
    }
}
