<?php
//echo automatic($javascript);
if($sql_func){
	$sql_func->close();
}
if($C['DEBUG']==1){
	debug::stop();
	debug::message();
}
//echo round(microtime(true)-$spentime1,6);
?>