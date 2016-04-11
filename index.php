<?php
define('PERMISSION','/');
include(dirname(__FILE__).'/incl/define.php');
//$r=load_pjt_mods();
//p($r);
//include('db_func.php');
//
//define('APP_PATH','myproject_1/');
//build::create();
//php动作区
$common_func=new common_func;
$sql_func=new $db_engine;

//echo $sql_func->db_size();
//echo $sql_func->pre;
//
//javascript脚本区
// $query="select * from `{$prefix}test`";
// $info=$sql_func->mselect($query);
// //p($info);
// $query="select `title` from `{$prefix}test` where `id`='4'";
// echo $sql_func->num($query);
// echo $sql_func->get_field($query);
$javascript=<<<st
<script>
In.ready('content',function() {

});
</script>
st;
//javascript脚本区
?>
<!--html body-->
<table width="95%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>我是body</td>
  </tr>
  <tr>
    <td>我是body</td>
  </tr>
  <tr>
    <td>我是body</td>
  </tr>
  <tr>
    <td>我是body</td>
  </tr>
</table>
<!--html body-->


<?php
include("close.php");
