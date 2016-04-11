<?php
define('PERMISSION','/');
include(dirname(__FILE__).'/incl/define.php');
$form_hash=load_service('form_hash',ROOM_SVC);
if($post){
	echo '提交后<br />';
	if($form_hash->check_hash()){
		echo 'hash正确';
	}
}
?>
<form method='post'>
<input type='text' name='user_name' value='张三' />
<br />
<?php $form_hash->field_hash();?>
<input type='submit' value='提交'>
</form>
<?php
include("close.php");
?>