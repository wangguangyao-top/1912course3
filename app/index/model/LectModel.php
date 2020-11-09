<?php namespace	app\index\model;
use	think\Model;
class LectModel extends Model{
    protected	$pk	=	'lect_id';
    //	设置当前模型对应的完整数据表名称
    protected	$table	=	'course_lect';
    //	设置当前模型的数据库连接
    protected	$connection	=	'db_config';
}
