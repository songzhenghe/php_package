<?php
define('PERMISSION','/');
include(dirname(__FILE__).'/../../../incl/define.php');
echo APP1_CSS;
br();
//include('tpl.class.php');
$tpl=new tpl(1);

$m=load_service('m');
$m->index();
br();

$common_func=new common_func;
$tpl->assign('abc','12345');	
$tpl->display();
		
include("close.php");
?>