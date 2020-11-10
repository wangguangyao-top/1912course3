<?php

namespace app\index\controller;

use app\index\model\RbacBased;
use think\Controller;
use think\Request;
use app\index\model\RbacRole;
use app\index\model\RbacRoleBased;

class Role extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data=RbacRole::where('is_del','=',1)->paginate(2,false,['requry'=>input()]);;
        $this->assign('data',$data);
        $this->assign('pagi',$data->render());
        if(Request()->isAjax()){
            $this->view->engine->layout(false);
            return view('index_ajax');
        }
        return view("index@role/index");
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        if(Request()->isAjax()){
            $role_name=$request->post("role_name");
            if($role_name==''){
                echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
                die;
            }
            $data=[
                'role_name'=>$role_name,
                'reg_time'=>time(),
            ];
            $find=RbacRole::where('role_name','=',"$role_name")->find();
            if($find){
                echo json_encode(['error'=>100002,'msg'=>'角色已存在']);
                die;
            }
            $res=RbacRole::insert($data);

            if($res){
                echo json_encode(['error'=>200,'msg'=>'OK']);
                die;
            }else{
                echo json_encode(['error'=>100001,'msg'=>'NO']);
                die;
            }
        }
        return view("index@role/create");
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
        $role_id=input('id');
        $data=RbacRole::where('role_id','=',$role_id)->find();
        $this->assign('data',$data);
        return view("index@role/edit");
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
        $role_id=$request->post("role_id");
        $role_name=$request->post("role_name");

        if($role_name==''||$role_id==''){
            echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
            die;
        }
        $find=RbacRole::where('role_name','=',"$role_name")->where('role_id','<>',$role_id)->find();
        if($find){
            echo json_encode(['error'=>100002,'msg'=>'权限名已存在']);
            die;
        }
        $data=[
            'role_name'=>$role_name,
            'up_time'=>time(),
        ];

        $res=RbacRole::where('role_id',$role_id)->update($data);

        if($res){
            echo json_encode(['error'=>200,'msg'=>'修改成功']);
            exit;
        }
        echo json_encode(['error'=>100001,'msg'=>'修改失败']);
        exit;
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

        $res= $res=RbacRole::where('role_id','=',$id)->update(['is_del'=>2]);;
        if($res){
            echo json_encode(['error'=>200,'msg'=>'删除成功']);
            exit;
        }
        echo json_encode(['error'=>100001,'msg'=>'删除失败']);
        exit;
    }

    /**
     * 赋予权限
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function roleBased(Request $request)
    {
        if(Request()->isAjax()){
            $role_id=$request->post('role_id');
            $based_id=$request->post('based_id');

            if(empty($role_id)||empty($based_id)){
                echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
                exit;
            }
            $data=[
                'role_id'=>$role_id,
                'based_id'=>$based_id
            ];
            $roleBased=RbacRoleBased::where('role_id',$role_id)->find();
            if($roleBased){
                $res=RbacRoleBased::where('role_id',$role_id)->update($data);
                if($res){
                    echo json_encode(['error'=>200,'msg'=>'修改成功']);
                    exit;
                }
                echo json_encode(['error'=>100001,'msg'=>'修改失败']);
                exit;
            }else{
                $res=RbacRoleBased::create($data);
                if($res){
                    echo json_encode(['error'=>200,'msg'=>'添加成功']);
                    exit;
                }
                echo json_encode(['error'=>100001,'msg'=>'添加失败']);
                exit;
            }
        }
        //
        $role_id=input('id');
        //查询角色信息
        $role=Rbacrole::where('role_id',$role_id)->find();
        $this->assign('role',$role);
        //查询关联表中的权限id
        $roleBased=RbacRoleBased::where('role_id',$role_id)->find();
        $based_ids=$roleBased['based_id'];
        $based_ids=explode(',',$based_ids);
        $this->assign('based_ids',$based_ids);
        //查询所有的权限
        $based=RbacBased::where('is_del',1)->select();
        $this->assign('based',$based);
        return view('role/roleBased');
    }
}
