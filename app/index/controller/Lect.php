<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\LectModel;

class Lect extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = LectModel::where('is_del',1)->select();
        return view("index@lect/index",['data'=>$data]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view("index@lect/create");
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
        $lect_image = $request->post('lect_image');
        $cate_id = $request->post('cate_id');
        $lect_resume = $request->post('lect_resume');
        $lect_style = $request->post('lect_style');
        $data = [
            'lect_name'=>$lect_name,
            'lect_image'=>$lect_image,
            'cate_id'=>$cate_id,
            'lect_resume'=>$lect_resume,
            'lect_style'=>$lect_style,
            'add_time'=>time(),
        ];
        $res = LectModel::insert($data);
        if($res){
            $this->success("添加成功","lect/index");
        }else{
            $this->error("添加失败","lect/create");
        }
    }

    public function upload(){
        //	获取表单上传文件	例如上传了001.jpg
        $file = request()->file('image');
        //	移动到框架应用根目录/uploads/	目录下
        $info = $file->move(	'../uploads');
        if($info){
            //	成功上传后	获取上传信息
            ////	输出	jpg
            echo	$info->getExtension();
            //	输出	20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo	$info->getSaveName();
            //	输出	42a79759f284b767dfcb2a0197904287.jpg
            echo	$info->getFilename();
        }else{
            //	上传失败获取错误信息
            echo	$file->getError();
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
        return view("index@lect/edit",['data'=>$data]);
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
        $lect_image = $request->post('lect_image');
        $cate_id = $request->post('cate_id');
        $lect_resume = $request->post('lect_resume');
        $lect_style = $request->post('lect_style');
        $data = [
            'lect_name'=>$lect_name,
            'lect_image'=>$lect_image,
            'cate_id'=>$cate_id,
            'lect_resume'=>$lect_resume,
            'lect_style'=>$lect_style,
            'add_time'=>time(),
        ];
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
