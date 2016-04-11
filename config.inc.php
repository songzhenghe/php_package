<?php
//数据库配置
$C['DB_DRIVER']=$db_driver;//数据库引擎
//$C['DB_DRIVER']='mysql';//数据库引擎
$C['DB_HOST']='localhost';//mysql主机 
$C['DB_PORT']='3306';//mysql端口
$C['DB_USER']='root';//mysql用户 
$C['DB_PWD']='root';//mysql密码 
$C['DB_CHAR']='utf8';//字符集 
$C['DB_NAME']='yi_system';//使用的数据库
$prefix="yi_";//数据表前缀
$C['DEBUG']=1;//是否开启调试模式 1开0关
$C['MGR']=1;   //是否开启文件管理员 1开0关（DEBUG也需打开）
$C['CHECK_CJ']=1;//是否检测config.js
$C['RECORD_SQL']=1;//record error sql
$C['PAGE_DATA_NUM']=20;//分页每页显示条数
$C['PAGE_NAV_NUM']=8;//页码导航显示条数
//常量配置
//define('DB_CACHE_TIME',60);//数据缓存有效时间单位是秒

$apps=array(0=>'runtime/',1=>'modules/',2=>'modules2/');
//0项目为系统设置服务
define('APP1_CSS',R_PATH.$apps[1].'static/css/');
define('APP1_JS',R_PATH.$apps[1].'static/js/');
define('APP1_IMG',R_PATH.$apps[1].'static/images/');
define('APP1_AJAX',R_PATH.$apps[1].'ajax/');

define('APP2_CSS',R_PATH.$apps[2].'static/css/');
define('APP2_JS',R_PATH.$apps[2].'static/js/');
define('APP2_IMG',R_PATH.$apps[2].'static/images/');
define('APP2_AJAX',R_PATH.$apps[2].'ajax/');


/*
$apps=array(1=>'home/',2=>'');
//后台
define('HOME_ROOT',ROOT.$apps[1]);
define('HOME_URL',R_PATH.$apps[1]);
define('HOME_CSS',R_PATH.$apps[1].'css/');
define('HOME_JS',R_PATH.$apps[1].'js/');
define('HOME_IMG',R_PATH.$apps[1].'images/');
define('HOME_SVC',ROOT.$apps[1].'services/');
define('HOME_AJAX',R_PATH.$apps[1].'ajax/');
//前台
define('ROOM_ROOT',ROOT.$apps[2]);
define('ROOM_URL',R_PATH.$apps[2]);
define('ROOM_CSS',R_PATH.$apps[2].'css/');
define('ROOM_JS',R_PATH.$apps[2].'js/');
define('ROOM_IMG',R_PATH.$apps[2].'images/');
define('ROOM_SVC',ROOT.$apps[2].'services/');
define('ROOM_AJAX',R_PATH.$apps[2].'ajax/');
*/
?>