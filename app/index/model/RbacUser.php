<?php
namespace app\index\model;
use think\Model;

class RbacUser extends Model{
    protected $table='rbac_admin';
    protected $pk='admin_id';
}
?>