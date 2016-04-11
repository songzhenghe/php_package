<?php
define('PERMISSION','/');
include('../../incl/define.php');

//一对多	
$a=array("table"=>"category","fields"=>"*","glue"=>"id","other"=>"t1.id as c_id");
$b=array("table"=>"article","fields"=>"*","glue"=>"category_id","other"=>"t2.id as a_id");
$where="t1.id=11";
p($sql_func->rselect($a,$b,$where));

//一对一
p($sql_func->id2name(1,"user::user_name,login_time::id"));

?>



<?php
include("close.php");
?>