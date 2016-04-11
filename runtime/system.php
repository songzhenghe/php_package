<?php
define('PERMISSION','/');
include('../incl/define.php');
$key=$get['key'];
$action=$get['action'];
if(!file_exists('key.php')) exit;
if($key==include('key.php')){
	if(in_array($action,array('cj','el'))){
		switch($action){
			case 'cj' :
				$url=URL;
				$app1_ajax=APP1_AJAX;
				$app2_ajax=APP2_AJAX;
				$configjs_file=ROOT.'runtime/config.js';
				$configjs_content=<<<st
var website_url='{$url}';
var app1_ajax='{$app1_ajax}';
var app2_ajax='{$app2_ajax}';
st;
			if(@file_put_contents($configjs_file,$configjs_content)){
				echo 1;
			}
			break;
			case 'el':
			$error_logfile=ROOT.'runtime/error_log.php';
			$error_logcontent=<<<st
<?php exit;?>

st;
			if(!file_exists($error_logfile)){
				if(@file_put_contents($error_logfile,$error_logcontent)){
					echo 1;
				}
			}else{
				$contents=file_get_contents($error_logfile);
				if(substr($contents,0,3)!='<?p'){
					if(@file_put_contents($error_logfile,$error_logcontent.$contents)){
						echo 1;
					}
				}
			}
			break;
		}
	}
	exit;
}
exit;