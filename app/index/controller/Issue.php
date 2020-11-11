<?php

namespace app\index\controller;

use Illuminate\Support\Facades\DB;
use think\Controller;
use think\Request;
use app\index\model\IssueModel;
use app\index\model\UserModel;

class Issue extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $user_name=input('user_name');
        $where=[];
        if($user_name){
            $where[]=['user_name','like',"%$user_name%"];
        }
        $data = IssueModel::leftjoin("course_user",'course_issue.user_id=course_user.user_id')->where('course_issue.is_del',1)->where($where)->paginate(2,false,['requry'=>input()]);
        //$data = IssueModel::select();
        //$user = UserModel::select();
        $query = request()->input();
        $this->assign('data',$data);

        $this->assign('pagi',$data->render());
        if(Request()->isAjax()){
            $this->view->engine->layout(false);
            return view('index_ajax');
        }
        return view("index@issue/index",['']);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view("index@issue/create");
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
        //
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
    public function delete()
    {
        $issue_id = input("issue_id");
        $where=[
            ['issue_id','=',$issue_id],//用户的id
            ['is_del','=',1]//没有被删除
        ];
        $res = IssueModel::where($where)->update(['is_del'=>2]);
        if($res){
            $this->success('删除成功','issue/index');
        }else{
            $this->error('删除失败','issue/index');
        }
    }
}
