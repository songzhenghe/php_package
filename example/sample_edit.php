<?php 
define('PERMISSION','/');
include('../../incl/define.php');
$common_func=new common_func;
$sql_func=new $db_engine;
include('session.php');
$_tablename='category';
//$_pk='id';
if($post['Submit']){
	$vali=validate::check($_tablename,$post,'mod',array('catid::='=>1,'id::!='=>23));//验证
	if($vali){
		$query=$sql_func->ready("update `{$prefix}{$_tablename}` set %s where `id`='{$post['id']}'");//准备
		$sql_func->update($query);
		exit;
	}else{
		$common_func->alert(implode('&',validate::getMsg()),$_SERVER['HTTP_REFERER']);	//固定用法
		exit;
	}
}
$id=$get['id'];
if($id!='' and $id>0){
	$query="select * from `{$prefix}{$_tablename}` where `id`='$id'";
	$info=$sql_func->select($query);
	if(!$info){
		$common_func->alert('数据不存在！');
		exit;
	}
	$info=safe_html($info);
}else{
	exit;	
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
<div class="t_center">编辑</div>
</div>
<div class="bottom">
<form action="" method="post" name="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right">名称：</td>
    <td align="left"><input name="title" type="text"  class="input" value="<?php echo $info["title"];?>"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
	<input name="Submit" type="submit" value="提交" class="button" />
	<input name='id' type='hidden' value='<?php echo $info['id'];?>' />
	</td>
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