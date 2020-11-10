<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\RbacBased;

class Based extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data=RbacBased::where('is_del','=',1)->paginate(2,false,['requry'=>input()]);;
        $this->assign('data',$data);
        $this->assign('pagi',$data->render());
        if(Request()->isAjax()){
            $this->view->engine->layout(false);
            return view('index_ajax');
        }
        return view("index@based/index");
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        if(Request()->isAjax()){
            $based_name=$request->post("based_name");
            $url=$request->post('url');
            $pid=$request->post("pid");
            if($based_name==''||$url==''||$pid==''){
                echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
                die;
            }
            $data=[
                'based_name'=>$based_name,
                'url'=>$url,
                'pid'=>$pid,
            ];
            $find=RbacBased::where('based_name','=',"$based_name")->find();
            if($find){
                echo json_encode(['error'=>100002,'msg'=>'权限名已存在']);
                die;
            }
            $res=RbacBased::where('is_del',1)->insert($data);

            if($res){
                echo json_encode(['error'=>200,'msg'=>'OK']);
                die;
            }else{
                echo json_encode(['error'=>100001,'msg'=>'NO']);
                die;
            }
        }
        $based=new RbacBased();
        $data=$based->select();
        $data=$this->CateInfo($data);
        $this->assign('data',$data);
        return view("index@based/create");
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
        $id=input('id');
        $data=RbacBased::where('based_id','=',$id)->find();
        $this->assign('data',$data);

        $based=new RbacBased();
        $date=$based->select();
        $date=$this->CateInfo($date);
        $this->assign('date',$date);
        return view("index@based/edit");
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
        $id=empty(input('based_id'))?0:input('based_id');
        $based_name=$request->post("based_name");
        $url=$request->post('url');
        $pid=$request->post("pid");
        if($based_name==''||$url==''||$pid==''){
            echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
            die;
        }
        $data=[
            'based_name'=>$based_name,
            'url'=>$url,
            'pid'=>$pid,
        ];
        $find=RbacBased::where('based_name','=',"$based_name")->where('based_id','<>',$id)->find();
        if($find){
            echo json_encode(['error'=>100002,'msg'=>'权限名已存在']);
            die;
        }
        $res=RbacBased::where('based_id',$id)->update($data);

        if($res){
            echo json_encode(['error'=>200,'msg'=>'OK']);
            die;
        }else{
            echo json_encode(['error'=>100001,'msg'=>'NO']);
            die;
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
        $id=empty(input('id'))?0:input('id');
        if(empty($id)){
            echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
            exit;
        }

        $res= $res=RbacBased::where('based_id','=',$id)->update(['is_del'=>2]);;
        if($res){
            echo json_encode(['error'=>200,'msg'=>'删除成功']);
            exit;
        }
        echo json_encode(['error'=>100001,'msg'=>'删除失败']);
        exit;
    }

    function CateInfo($data,$pid=0,$level=1){
        static $info=[];
        foreach($data as $k=>$v){
            if($v['pid']==$pid){
                $v['level']=$level;
                $info[]=$v;
                $this->CateInfo($data,$v['based_id'],$v['level']+1);
            }
        }
        return $info;
    }
}
