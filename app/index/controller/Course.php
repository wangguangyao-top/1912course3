<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Category as CategoryModel;
use app\index\model\Course as CourseModel;
use app\index\model\LectModel;
use app\index\model\CatalogModel;

class Course extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $data=input();
        $where=[];
        if(!empty($data['course_name'])){
            $where[]=["course_name","like","%".$data['course_name']."%"];
        }
        $course_info=CourseModel::
        leftjoin("course_category","course_course.cate_id=course_category.cate_id")
        ->leftjoin("course_lect","course_course.lect_id=course_lect.lect_id")
        ->leftjoin("course_catalog","course_course.catalog_id=course_catalog.catalog_id")
        ->where(["course_course.is_del"=>1])
        ->where($where)
        ->paginate(4,false,['requry'=>input()]);
        return view("index@course/index",compact("course_info"));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //课程分类
        $CategoryModel=new CategoryModel;
        $data1=$CategoryModel->select();
        $CateInfo1=$this->CateInfo1($data1);
        $this->assign("CateInfo1",$CateInfo1);
        //目录分类
        $CatalogModel=new CatalogModel;
        $data2=$CatalogModel->select();
        $CateInfo2=$this->CateInfo2($data2);
        $this->assign("CateInfo2",$CateInfo2);
        //讲师分类
        $LectModel=new LectModel;
        $data3=$LectModel->select();
        $this->assign("data3",$data3);
        return view("index@course/create");
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $CourseModel=new CourseModel;
        $data=$request->post();
//        $img=Request()->file('course_image');
//        if($_FILES['course_image']['error']==0){
//            $img=$this->uploads($img);
//            $data['course_image']=$img;
//        }
        $data['add_time']=time();
        $res=$CourseModel->save($data);
        if($res){
            $this->success("添加成功",url('/index/course/index'));
        }else{
            $this->error("添加失败");
        }
    }
//    public function uploads($files){
//        //$files=request()->file($file);
//        $info=$files->move('./uploads');
//        if($info){
//            return $info->getSaveName();
//        }else{
//            return false;
//        }
//
//    }


    //文件上传
    public function goodsimg(Request $request){
        $arr = $_FILES["Filedata"];
        $tmpName = $arr['tmp_name'];
        $ext = explode(".",$arr['name'])[1];
        $newFileName = md5(rand(10000,99999)).".".$ext;
        $newFilePath = "./uploads/".$newFileName;
        move_uploaded_file($tmpName,$newFilePath);
        $newFilePath = trim($newFilePath,".");
        return $newFilePath;
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
    public function edit($course_id)
    {
        //课程分类
        $CategoryModel=new CategoryModel;
        $data1=$CategoryModel->select();
        $CateInfo1=$this->CateInfo1($data1);
        $this->assign("CateInfo1",$CateInfo1);
        //目录分类
        $CatalogModel=new CatalogModel;
        $data2=$CatalogModel->select();
//        print_r($data2);echo "<hr>";
        $CateInfo2=$this->CateInfo2($data2);
        $this->assign("CateInfo2",$CateInfo2);
        //讲师分类
        $LectModel=new LectModel;
        $data3=$LectModel->select();
        $this->assign("data3",$data3);


        $course_info=CourseModel::where(["course_id"=>$course_id])->find();
        return view("index@course/edit",compact("course_info"));
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
        $course_id=$request->post("course_id");
        $data=$request->post();

//        $img=Request()->file('course_image');
//        if($_FILES['course_image']['error']==0){
//            $img=$this->uploads($img);
//            $data['course_image']=$img;
//        }
        $res=CourseModel::where(["course_id"=>$course_id])->update($data);
        if($res){
            $this->success("修改成功",url('/index/course/index'));
        }else{
            $this->success("修改失败");
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request,$course_id)
    {
        $res=CourseModel::where(["course_id"=>$course_id])->update(["is_del"=>2]);
        if($res){
            echo json_encode(["code"=>200,"msg"=>"删除成功"]);die;
        }else{
            echo json_encode(["code"=>1,"msg"=>"删除失败"]);die;
        }
    }
    //课程无限极分类
    function CateInfo1($data,$pid=0,$level=1){
        static $info=[];
        foreach($data as $k=>$v){
            if($v['p_id']==$pid){
                $v['level']=$level;
                $info[]=$v;
                $this->CateInfo1($data,$v['cate_id'],$v['level']+1);
            }
        }
        return $info;
    }
    //目录无限极分类
    function CateInfo2($data,$pid=0,$level=1){
        static $info=[];
        foreach($data as $k=>$v){
            if($v['p_id']==$pid){
                $v['level']=$level;
                $info[]=$v;
                $this->CateInfo2($data,$v['catalog_id'],$v['level']+1);
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
    //上传视频
    public function video(){
        $fileName = $_REQUEST["filename"];
        $tmpName = $_FILES["page"]["tmp_name"];
        $content = file_get_contents($tmpName);
        $img='./images/'.date("Y/m/d/");
        $img3=$img.$fileName;
        if(!is_dir($img)){
            mkdir($img,0777,true);
        }
        file_put_contents($img3,$content,FILE_APPEND);
        $arr=[
            "res"=>true
        ];
        $img3=trim($img3,'.');
        echo json_encode($img3);
    }
}
