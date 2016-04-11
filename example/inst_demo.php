<?php
define('PERMISSION','/');
include('../incl/define.php');

if($_POST['submit']){

	$config=array(
		'field_name'=>'myfile',
		'upload_path'=>'./uploads/',
		'allow_type'=>array('.jpg','.pdf'),//数组
		'max_size'=>'1',
		'unit'=>'M',
		'israndname'=>false,
		'iscreatedir'=>true,
		'iscreatebydate'=>true,
		'isoverride'=>true
	);

	$r=load_instance('upload',$config);
	echo '<pre>';
	print_r($r);
	echo '</pre>';

}

?>
<form method='post' enctype='multi-part/form-data'>
	<input type='file' name='myfile'/><br />
	<input type='submit' name='submit' />
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000000000000000">
</form>