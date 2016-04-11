<?php 
define('PERMISSION','/');
include('../../incl/define.php');
$common_func=new common_func;
$sql_func=new $db_engine;
include('session.php');
$id=$get['id'];
$_tablename='category';
if($id!='' and $id>0){
	$query="delete from `{$prefix}{$_tablename}` where `id`='$id'";
	$sql_func->delete($query);
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
loadcss('colorbox');
loadjs('jquery');
loadjs('jquery.colorbox');
?>
<script>
	$(document).ready(function(){
		$(".example1").colorbox({width:"400px", height:"300px", iframe:true, opacity:0.3});
		$(".example2").colorbox({width:"600px", height:"400px", iframe:true, opacity:0.3});
	});
</script>
</head>
<body>
<div class="container">
<div class="top">
<div class="t_left"></div>
<div class="t_right"></div>
<div class="t_center">列表</div>
</div>
<div class="bottom">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
$query="select * from `{$prefix}article` order by `id` desc";
$common_func->__pages($query);
if($total_num>0){
  $info=$sql_func->mselect($query);
  $info=safe_html($info);
  $n=count($info);
  if($n>0){
?>
<tr>
    <td align="center">序号</td>
    <td align="center">名称</td>
    <td align="center">添加时间</td>
    <td align="center">编辑</td>
    <td align="center">删除</td>
</tr>
<?php
  	for($i=0;$i<$n;$i++){
?>
  <tr>
    <td align="center"><?php echo $i+1;?></td>
    <td align="center"><?php echo $info[$i]["title"];?></td>
    <td align="center"><?php echo date("Y-m-d H:i:s",$info[$i]["add_time"]);?></td>
    <td align="center"><a class="example1" href="category_edit.php?id=<?php echo $info[$i]["id"] ?>"><img src="<?php echo B_IMG;?>bianji.gif" /></a></td>
    <td align="center"><a href="?id=<?php echo $info[$i]["id"] ?>" onclick="return confirm('确定删除吗？');"><img src="<?php echo B_IMG;?>shanchu.gif" /></a></td>
  </tr>
 <?php
	}
  ?>
  <tr>
    <td colspan="5" align="center"><?php $common_func->pages();?></td>
  </tr>
<?php
	}else{
?>
  <tr>
    <td colspan="5" align="center">页码不存在！</td>
  </tr>
<?php	
	}
}else{
?>
  <tr>
    <td colspan="5" align="center">无内容！</td>
  </tr>
<?php
}
?>
</table>

</div>
</div>
</body>
</html>
<?php
include("close.php");
?>