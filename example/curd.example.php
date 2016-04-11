------------------------------------------------------增
<?php
define('PERMISSION','/');
include('../../incl/define.php');
if($post['Submit']){
	$vali=validate::check("a2",$post,"add");//验证
	if($vali){
		$query=$sql_func->ready("insert into `{$prefix}a2` %s");//准备
		$sql_func->insert($query);
		exit;
	}else{
		$common_func->alert(implode("&",validate::getMsg()),$_SERVER['HTTP_REFERER']);	//固定用法
		exit;
	}
}
?>

<?php
include("close.php");
?>
------------------------------------------------------增

------------------------------------------------------删
<?php
define('PERMISSION','/');
include('../../incl/define.php');
$tid=$get["tid"];
if($tid!="" and $tid>0){
		$query="delete from `{$prefix}a2` where `tid`='$tid'";
		$sql_func->delete($query);
		exit;
}
?>

<?php
include("close.php");
?>
------------------------------------------------------删

------------------------------------------------------改
<?php
define('PERMISSION','/');
include('../../incl/define.php');
$tid=$get["tid"];
if($tid!="" and $tid>0){
	$query="select * from `{$prefix}a2` where `tid`='$tid'";
	$info=$sql_func->select($query);
}else{
	exit;	
}
if($post['Submit']){
	$vali=validate::check("a2",$post,"mod");//验证
	if($vali){
		$query=$sql_func->ready("update `{$prefix}a2` set %s where `tid`='$tid'");//准备
		$sql_func->update($query);
		exit;
	}else{
		$common_func->alert(implode("&",validate::getMsg()),$_SERVER['HTTP_REFERER']);	//固定用法
		exit;
	}
}
?>

<?php
include("close.php");
?>
------------------------------------------------------改

------------------------------------------------------查
<?php
	$query="select * from `{$prefix}a2` order by `tid` asc";
	$common_func->__pages($sql_func,$query,array(),15);
	if($total_num>0){
?>
<?php $common_func->pages($total_num,$page_id,$add,$pagesize);?>
  <?php
  $info=$sql_func->mselect($query); 
  	for($i=0;$i<count($info);$i++){
  ?>
  
  <?php
	}
  ?>

<?php $common_func->pages($total_num,$page_id,$add,$pagesize);?>

<?php
	}else{
?>

<?php
	}
?>

<?php
include("close.php");
?>
------------------------------------------------------查

