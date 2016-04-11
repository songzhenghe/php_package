<?php 
define('PERMISSION','/');
include('../../incl/define.php');
$common_func=new common_func;
$sql_func=new $db_engine;
include('session.php');
if($post['Submit']){
	$_tablename='category';
	$vali=validate::check($_tablename,$post,'add');//验证
	if($vali){
		$post['add_time']=time();
		$query=$sql_func->ready("insert into `{$prefix}{$_tablename}` %s",$post);//准备
		$sql_func->insert($query);
		exit;
	}else{
		$common_func->alert(implode('&',validate::getMsg()),$_SERVER['HTTP_REFERER']);	//固定用法
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站管理系统</title>
<?php
loadcss('public');
loadcss('content');
?>
</head>
<body>
<div class="container">
<div class="top">
<div class="t_left"></div>
<div class="t_right"></div>
<div class="t_center">新增</div>
</div>
<div class="bottom">
<form action="" method="post" name="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right">名称：</td>
    <td align="left"><input name="title" type="text"  class="input"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="Submit" type="submit" value="提交" class="button" /></td>
    </tr>
</table>
</form>
</div>
</div>
</body>
</html>
<?php
include("close.php");
?>