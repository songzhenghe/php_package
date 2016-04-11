<?php
//$spentime1=microtime(true);
include_once('permission.php');
header("Content-Type:text/html;charset=utf-8");
session_start();
date_default_timezone_set('Asia/Shanghai');//时区设置
define('GPC',get_magic_quotes_gpc());//自动转义状态的常量
define('ROOT',str_replace('\\','/',realpath(dirname(dirname(__FILE__)))).'/');//格式 E:/wwwroot/yi_cms/
//自动定义网站相对路径与绝对路径
$dir=substr(str_replace('\\','/',dirname(__FILE__)),strlen($_SERVER['DOCUMENT_ROOT']));
if($dir==''){
	$dir='/';
}else{
	$dir=substr($dir,0,strrpos($dir,'/')+1);
}
define('R_PATH',$dir);//网站相对于根目录的路径，通常为"/"或其它如："/folder_name/"，以"/"结束。
define('URL','http://'.str_replace('//','/',$_SERVER['HTTP_HOST'].'/'.R_PATH));//网站URL绝对路径 如：http://www.example.com/yi_system/
unset($dir);
//
define('P_CSS',R_PATH.'public/css/');//网站公共css文件夹地址 如：/yi_system/yi_cms/css/
define('P_JS',R_PATH.'public/js/');//网站公共js文件夹地址 如：/yi_system/yi_cms/js/
define('P_IMG',R_PATH.'public/images/');//网站公共图片文件夹地址 如：/yi_system/yi_cms/images/
//
define('INCL',ROOT.'incl/');//定义文件包含目录
define('MGR_PATH',URL.'incl/');//定义文件管理员目录

if(function_exists('mysqli_connect')){
	$db_driver='mysqli';
}else{
	$db_driver='mysql';
}
include(ROOT.'config.inc.php');//引入配置文件
if($C['CHECK_CJ']){
	if(!file_exists(ROOT.'runtime/config.js')){
		exit('config.js不存在！');
	}
}
unset($db_driver);
if($C['DB_DRIVER']=='mysqli'){
	$db_engine='sqli_func';
}else{
	$db_engine='sql_func';
}
include(INCL.'functions.inc.php');
//设置包含目录
$include_path=array(get_include_path(),
					ROOT,//网站根目录
					INCL,//include目录
					ROOT.'classes/',  //扩展类库目录
					ROOT.'expresses/',  //expresses
					ROOT.'functions/',  //func
					ROOT.'rules/'     //数据库验证回调类
					);
set_include_path(implode(PATH_SEPARATOR,$include_path));
unset($include_path);
//设置Debug模式
if($C['DEBUG']==1){
	debug::start();                               //开启脚本计算时间
	set_error_handler(array('debug', 'Catcher'),E_ALL); //设置捕获系统异常 E_ALL & ~E_NOTICE  E_ALL & ~E_NOTICE | E_STRICT
}else{
	ini_set('display_errors', 'Off'); 		//屏蔽错误输出
	ini_set('log_errors', 'On');             	//开启错误日志，将错误报告写入到日志中
	ini_set('error_log', ROOT.'runtime/error_log.php'); //指定错误日志文件
}
//自动加载类 
function __autoload($className){    
		include strtolower($className).'.class.php';
		global $C;
		if($C['DEBUG']==1){
			debug::addmsg("<b> {$className} </b>类", 1);  //在debug中显示自动包含的类
		}
}
//设置输出Debug模式的信息
if($C['DEBUG']==1){
	debug::addmsg("<strong style=\"color:red;\">当前脚本页地址：".$_SERVER['SCRIPT_NAME']."</strong>");
	debug::addmsg("<strong style=\"color:red;\">服务器session设置：".ini_get('session.auto_start')."</strong>");
	debug::addmsg("开启会话Session 会话名称: ".session_name()." 会话ID：".session_id());
	debug::addmsg("GPC：".GPC);  
	debug::addmsg("网站根目录ROOT：".ROOT);  
	debug::addmsg("Web服务器根到项目的根R_PATH：".R_PATH);
	debug::addmsg("网站URL绝对路径URL：".URL);
}
if(!GPC){
	if($_GET){
		$get=$_GET=addslashes_array($_GET);
	}
	if($_POST){
		$post=$_POST=addslashes_array($_POST);
	}
	if($_COOKIE){
		$cookie=$_COOKIE=addslashes_array($_COOKIE);
	}
}else{
	if($_GET){
		$get=$_GET;
	}
	if($_POST){
		$post=$_POST;
	}
	if($_COOKIE){
		$cookie=$_COOKIE;
	}
}
//htmlob_begin();
?>