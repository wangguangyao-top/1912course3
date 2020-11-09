<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Category as CategoryModel;
use app\index\model\Course as CourseModel;

class Category extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $CategoryModel=new CategoryModel;
        $data=$CategoryModel->select();
        $CateInfo=$this->CateInfo($data);
        $this->assign("CateInfo",$CateInfo);
        return view("index@category/index");
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
        return view("category/create");
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $CategoryModel=new CategoryModel;
        $data=$request->post();
        if(empty($data['cate_name'])){
            $this->error("分类名称不能为空");
        }else{
            $where=[
                ['cate_name',"=",$data['cate_name']],
            ];
            $arr=$CategoryModel->where($where)->find();
            if($arr){
                $this->error("分类名称已存在");
            }
        }
        $res=$CategoryModel->save($data);
        if($res){
            $this->success("添加成功",url('category/index'));
        }else{
            $this->error("添加失败");
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
    public function edit()
    {
        $CategoryModel=new CategoryModel;
        $cate_id=Request()->get("cate_id");
        $data=$CategoryModel->where("cate_id","=",$cate_id)->find();
        $this->assign("data",$data);
        $CategoryModel=new CategoryModel;
        $data=$CategoryModel->select();
        $CateInfo=$this->CateInfo($data);
        $this->assign("CateInfo",$CateInfo);
        return view("index@category/edit",['cate_id'=>$cate_id]);
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
        $CategoryModel=new CategoryModel;
        $data=$request->post();
        if(empty($data['cate_name'])){
            $this->error("分类名称不能为空");
        }else{
            $where=[];
            $where[]= ['cate_name',"=",$data['cate_name']];
            $where[]=['cate_id','<>',$data['cate_id']];
            $arr=$CategoryModel->where($where)->find();
            if($arr){
                $this->error("分类名称已存在");
            }
        }
        $res=$CategoryModel->where("cate_id","=",$data['cate_id'])->update($data);
        if($res!==false){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        $CategoryModel=new CategoryModel;
        $CourseModel=new CourseModel;
        $cate_id=input("cate_id");
        $where=[
            ['p_id',"=",$cate_id],
        ];
        $res=$CategoryModel->where($where)->count();
        if($res>0){
            $this->error("此分类下有子分类,不能删除");
        }
        $where1=[
            ['cate_id',"=",$cate_id],
        ];
        $res1=$CourseModel->where($where1)->count();
        //dump($res1);die;
        if($res1>0){
            $this->error('此分类下有商品,不能删除');
        }
        $where2=[
            ["cate_id","=",$cate_id],
        ];
        $res2=$CategoryModel->where($where2)->delete();
        if($res2){
            $this->success('删除成功');
        }else{
            $this->error("删除失败");
        }
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
