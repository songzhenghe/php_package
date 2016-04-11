<?php
class viewer{
	public $common_func;
	public $sql_func;
	function __construct(){
		global $common_func;
		$this->common_func=$common_func;		
	}
	function index(){
		echo "我是模板的index();";
		//$this->common_func=new common_func;
		$this->name='宋正河';
		$this->arr=array(1,2,3);
		include('index.tpl.php');
	}
}
?>