<?php namespace	app\index\model;
use	think\Model;
class MessageModel extends Model{
    protected	$pk	=	'message_id';
    //	设置当前模型对应的完整数据表名称
    protected	$table	=	'course_message';
    //	设置当前模型的数据库连接
    protected	$connection	=	'db_config';
}
