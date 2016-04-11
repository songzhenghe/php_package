<?php
define('PERMISSION','/');
include(dirname(__FILE__).'/../../incl/define.php');

$javascript=<<<st
<script>
In.ready('content',function() {

});
</script>
st;
$myhtml = new myhtml;
echo $myhtml
	->_html()
	->_head('js',return_include('js_autoload.php'))
	->_head('css',return_include('css_autoload.php'))
	->_head('js',$javascript)
	->_body(return_include('tpl/user.tpl.php'))
	->_go();
include("close.php");
?>