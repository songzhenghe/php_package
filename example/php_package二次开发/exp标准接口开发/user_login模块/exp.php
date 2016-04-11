<?php
define('PERMISSION','/');
include(dirname(__FILE__).'/incl/define.php');
$user=load_service('i_user_login',ROOM_SVC);
$_SESSION['code']=1234;
$in_data=array('user_name'=>'admin','user_password'=>'admin','code'=>'1234');
echo $user->user_login($in_data);
echo '<br />';
//echo $user->user_logout();
$a=$user->user_session();
p($a);
?>

<?php
include("close.php");
?>