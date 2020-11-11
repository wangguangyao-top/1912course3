<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\MessageModel;


class Message extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $message_title=input('message_title');
        $where=[];
        if($message_title){
            $where[]=['message_title','like',"%$message_title%"];
        }
        $data = MessageModel::where('is_del',1)->where($where)->paginate(2,false,['requry'=>input()]);
        $query = request()->input();
        $this->assign('data',$data);

        $this->assign('pagi',$data->render());
        if(Request()->isAjax()){
            $this->view->engine->layout(false);
            return view('index_ajax');
        }
        return view("index@message/index",['']);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view("index@message/create");
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $message_title = $request->post('message_title');
        $message_name = $request->post('message_name');
        $is_hut = $request->post('is_hut');
        $data = [
          'message_title'=>$message_title,
          'message_name'=>$message_name,
            'is_hut'=>$is_hut
        ];
        $res = MessageModel::insert($data);
        if($res){
            $this->success('添加成功');
        }else{
            $this->error('添加失败','message/create');
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
        $message = MessageModel::where('message_id',$id)->find();
        return view("index@message/edit",['message'=>$message]);
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
        $message_title = $request->post('message_title');
        $message_name = $request->post('message_name');
        $is_hut = $request->post('is_hut');
        $message_id = $request->post('message_id');
        $data = [
            'message_title'=>$message_title,
            'message_name'=>$message_name,
            'is_hut'=>$is_hut
        ];
        $res = MessageModel::where('message_id',$message_id)->update($data);
        if($res){
            $this->success('修改成功','message/index');
        }else{
            $this->error('修改失败','message/update');
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
        $message_id = input("message_id");
        $where=[
            ['message_id','=',$message_id],//用户的id
            ['is_del','=',1]//没有被删除
        ];
        $res = MessageModel::where($where)->update(['is_del'=>2]);
        if($res){
            $this->success('删除成功','message/index');
        }else{
            $this->error('删除失败','message/index');
        }
    }
}
