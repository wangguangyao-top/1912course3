<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\CatalogModel;

class Catalog extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $info=CatalogModel::where(["is_del"=>1])->select();
        $cata_info=$this->CataInfo($info);
        return view("index@catalog/index",compact("cata_info"));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $info=CatalogModel::select();
        $catalog=$this->CataInfo($info);
        return view("index@catalog/create",compact("catalog"));
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data=$request->post();
        $data["add_time"]=time();
        $res=CatalogModel::insert($data);
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
    public function edit($catalog_id)
    {
        $cata_edit=CatalogModel::where(["catalog_id"=>$catalog_id])->find();
        $info=CatalogModel::select();
        $catalog=$this->CataInfo($info);
        return view("index@catalog/edit",compact("catalog","cata_edit"));
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request,$catalog_id)
    {
        $edit_data=$request->post();
        $res=CatalogModel::where("catalog_id",$catalog_id)->update($edit_data);
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
    public function delete($catalog_id)
    {
        $where=[
            "p_id"=>$catalog_id,
            "is_del"=>1
        ];
        $data=CatalogModel::where($where)->count();
        if($data>0){
            echo json_encode(["code"=>1,"msg"=>"目录下边有内容不可删除"]);die;
        }
        $res=CatalogModel::where("catalog_id",$catalog_id)->update(["is_del"=>2]);
        if($res){
            echo json_encode(["code"=>200,"msg"=>"删除成功"]);die;
        }else{
            echo json_encode(["code"=>1,"msg"=>"删除失败"]);die;
        }
    }

    //无限极分类
    function CataInfo($data,$p_id=0,$level=1){
        static $info=[];
        foreach($data as $k=>$v){
            if($v['p_id']==$p_id){
                $v['level']=$level;
                $info[]=$v;
                $this->CataInfo($data,$v['catalog_id'],$v['level']+1);
            }
        }
        return $info;
    }
}
