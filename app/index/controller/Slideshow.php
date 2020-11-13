<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\SlideModel;
class Slideshow extends Controller
{
    /**
     * 添加
     */
    public function index(Request $request){
        return view('slideshow/slideshow');
    }
    public function uploader(){
        $file=$_FILES['Filedata'];
        $tmp_name=$file['tmp_name'];
        $imgType=explode('.',$file['name']);
        $imgType=array_pop($imgType);
        $img='./images/'.date("Y/m/d/");
        $img2=date('Ymds').uniqid().'.'.$imgType;
        $img3=$img.$img2;
        if(!is_dir($img)){
            mkdir($img,0777,true);
        }
        if(!file_exists($img3)) {
            move_uploaded_file($tmp_name,$img3);
        }
        $img=trim($img3,'.');
        return $img;

    }
    public function add(Request $request){
        $data=$request->post();
        $data['slide_images']=trim($data['slide_images'],',');
        $data['add_time']=time();
        $slid=new SlideModel();
        $add=$slid->insert($data);
        if($add){
            echo json_encode(['code'=>200,'msg'=>'OK']);
            die;
        }else{
            echo json_encode(['code'=>1000,'msg'=>'NO']);
            die;
        }
    }
    public function show(){
        $slid=new SlideModel();
        $show=$slid->where('is_del','=',1)->paginate(2);
        return view('/slideshow/show',['data'=>$show]);
    }
    public function seek(Request $request){
        $slidde_url=$request->slidde_url;
        $where=[];
        $where[]=['is_del','=',1];
        if(!$slidde_url){
            echo json_encode(['code'=>1000,'msg'=>'缺少参数']);
            die;
        }
        $where[]=['slide_url','like',"%".$slidde_url."%"];
        $slid=new SlideModel();
        $show=$slid->where($where)->select()->toArray();
        if($show){
            echo json_encode(['code'=>200,'msh'=>'ok','data'=>$show]);
            die;
        }
    }
    public function del(Request $request){
        $id=$request->id;
        $where=[];
        $where[]=['is_del','=',1];
        $where[]=['slide_id','=',$id];
        $slid=new SlideModel();
        $del=$slid->where($where)->update(['is_del'=>2]);
        if($del){
            echo json_encode(['code'=>200,'msg'=>'ok']);
            die;
        }

    }
    public function update(Request $request){
        if($request->isAjax() && $request->isPost()){
            $data=$request->post();
            $data['slide_images']=trim($data['slide_images'],',');
            $where=[];
            $where[]=['is_del','=',1];
            $where[]=['slide_id','=',$data['slide_id']];
            $slid=new SlideModel();
            $upd=$slid->where($where)->update($data);
            if($upd!==false){
                echo json_encode(['code'=>200,'msg'=>'OK']);
                die;
            }
        }
        $id=$request->id;
        $where=[];
        $where[]=['is_del','=',1];
        $where[]=['slide_id','=',$id];
        $slid=new SlideModel();
        $show=$slid->where($where)->find();
        if(!$show){
            echo json_encode(['code'=>1000,'msh'=>'ID不存在']);
            die;
        }
        return view('/slideshow/update',['data'=>$show]);
    }
}
