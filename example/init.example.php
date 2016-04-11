<?php
define('PERMISSION','/');
include(dirname(__FILE__).'/incl/define.php');
//
//php动作区
echo "123";
//
//javascript脚本区
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
?>