<?php
class form_hash {
	//生成一个hash
	public function create_hash(){
		$_SESSION['HASH']=md5(rand());
		return $_SESSION['HASH'];
	}
	//删除hash
	public function delete_hash(){
		unset($_SESSION['HASH']);
	}
	public function get_hash(){
		return $_SESSION['HASH'];
	}
	//检查hash是否正确
	public function check_hash(){
		if($_SESSION['HASH']!=$_POST['ihash_2013']){
			exit('hash error!');
		}
		$this->delete_hash();
		return true;
	}
	//生成一个表单隐藏字段
	public function field_hash(){
		if(!isset($_SESSION['HASH'])){
			$this->create_hash();
		}
		$value=$_SESSION['HASH'];
		echo "<input type='hidden' name='ihash_2013' value='{$value}' />\n";
	}
}