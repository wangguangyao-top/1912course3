<?php

namespace app\index\controller;

use app\index\model\RbacBased;
use app\index\model\RbacRole;
use app\index\model\RbacRoleBased;
use think\Controller;
use think\Request;
use app\index\model\RbacUser;
use app\index\model\RbacUserRole;
use think\Db;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data=RbacUser::where('is_del','=',1)->paginate(2,false,['requry'=>input()]);;
        $this->assign('data',$data);
        $this->assign('pagi',$data->render());
        if(Request()->isAjax()){
            $this->view->engine->layout(false);
            return view('index_ajax');
        }
        return view("index@user/index");
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        if($request->isAjax() && $request->isPost()){
            $admin_name=empty($request->post("admin_name"))?"":$request->post("admin_name");
            $pwd=empty($request->post('pwd'))?'':$request->post('pwd');
            if($admin_name==''||$pwd==''){
                echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
                die;
            }
            $data=[
                'admin_name'=>$admin_name,
                'password'=>$pwd,
                'reg_time'=>time(),
            ];
            $find=RbacUser::where('admin_name','=',"$admin_name")->find();
            if($find){
                echo json_encode(['error'=>100002,'msg'=>'用户名已存在']);
                die;
            }
            $res=RbacUser::insert($data);

            if($res){
                echo json_encode(['error'=>200,'msg'=>'OK']);
                die;
            }else{
                echo json_encode(['error'=>100001,'msg'=>'NO']);
                die;
            }
        }
        return view("index@user/create");
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
        $admin_id=input('id');
        $data=RbacUser::where('admin_id','=',$admin_id)->find();
        $this->assign('data',$data);
        return view("index@user/edit");
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
        $admin_id=$request->post("id");
        $admin_name=$request->post("admin_name");
        $pwd=$request->post('pwd');
        if($admin_name==''||$pwd==''||$admin_id==''){
            echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
            die;
        }
        $find=RbacUser::where('admin_name','=',"$admin_name")->where('admin_id','<>',$admin_id)->find();
        if($find){
            echo json_encode(['error'=>100002,'msg'=>'权限名已存在']);
            die;
        }
        $data=[
            'admin_name'=>$admin_name,
            'password'=>$pwd,
            'up_time'=>time(),
        ];

        $res=RbacUser::where('admin_id',$admin_id)->update($data);

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
    public function delete()
    {
        $id=empty(input('id'))?0:input('id');
        if(empty($id)){
            echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
            exit;
        }

        $res= $res=RbacUser::where('admin_id','=',$id)->update(['is_del'=>2]);;
        if($res){
            echo json_encode(['error'=>200,'msg'=>'删除成功']);
            exit;
        }
        echo json_encode(['error'=>100001,'msg'=>'删除失败']);
        exit;

    }

    /**
     * 赋予角色
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function userRole(Request $request)
    {
        if(Request()->isAjax()){
            $admin_id=$request->post('admin_id');
            $role_id=$request->post('role_id');
//            dump($admin_id);die;
            if(empty($admin_id)||empty($role_id)){
                echo json_encode(['error'=>100003,'msg'=>'参数缺失']);
                exit;
            }
            $data=[
                'admin_id'=>$admin_id,
                'role_id'=>$role_id
            ];
            $adminRole=RbacUserRole::where('admin_id',$admin_id)->find();
            if($adminRole){
                $res=RbacUserRole::where('admin_id',$admin_id)->update($data);
                if($res){
                    echo json_encode(['error'=>200,'msg'=>'修改成功']);
                    exit;
                }
                echo json_encode(['error'=>100001,'msg'=>'修改失败']);
                exit;
            }else{
                $res=RbacUserRole::create($data);
                if($res){
                    echo json_encode(['error'=>200,'msg'=>'添加成功']);
                    exit;
                }
                echo json_encode(['error'=>100001,'msg'=>'添加失败']);
                exit;
            }
        }
        //
        $admin_id=input('id');
        //查询管理员信息
        $admin=RbacUser::where('admin_id',$admin_id)->find();
        $this->assign('admin',$admin);
        //查询关联表中的角色id
        $userRole=RbacUserRole::where('admin_id',$admin_id)->find();
        $role_ids=$userRole['role_id'];
        $role_ids=explode(',',$role_ids);
        $this->assign('role_ids',$role_ids);
        //查询所有的角色
        $role=RbacRole::where('is_del',1)->select();
        $this->assign('role',$role);
        return view('user/userRole');
    }

    /**
     * 查看权限
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function userBased()
    {
        $admin_id=input('id');
        //通过管理员id查询所拥有的角色id
        $userRole=RbacUserRole::where('admin_id',$admin_id)->find();
        $role_ids=$userRole['role_id'];
        $role_ids=explode(',',$role_ids);
        $role_names=[];
        foreach($role_ids as $k=>$v){
            $userRole=RbacRole::where('role_id',$v)->find();
            $role_name=$userRole['role_name'];
            $role_names[]=$role_name;
        }


        $this->assign('role_names',$role_names);


        return view('user/userBased');
    }
}
