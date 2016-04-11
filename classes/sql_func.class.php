<?php
include_once("permission.php");
class sql_func extends db{////////
	//数据库连接句柄 
	//数据库连接
	public function __construct(){ 
		parent::__construct();
		global $C; 
		$link=$this->connect($C['DB_HOST'].':'.$C['DB_PORT'], $C['DB_USER'], $C['DB_PWD']) or exit('数据库连接错误！'.$this->error()); 
		$this->link=$link; 
		$this->select_db($C['DB_NAME']) or exit('数据库选择错误！'.$this->error()); 
		$this->query("SET NAMES {$C['DB_CHAR']}");
		
		if($C['DEBUG']==1){
			//$this->desc();
		}
	}
	//数据库连接

	function connect($host,$username,$password,$database='',$port=''){
		return mysql_connect($host,$username,$password);	
	}
	function error(){
		return mysql_error();	
	}
	function select_db($name){
		return mysql_select_db($name);
	}
	function query($sql){
		global $C;
		$r=mysql_query($sql);
		if($r==FALSE and $C['RECORD_SQL']==TRUE){
			$this->record_sql($sql);
			echo '<br />'.$this->error().'<br />';
			exit;
		}
		return $r;
	}
	function fetch_array($result){
		return mysql_fetch_array($result,MYSQL_ASSOC);
	}
	function fetch_assoc($result){
		return mysql_fetch_assoc($result);
	}
	function fetch_row($result){
		return mysql_fetch_row($result);
	}
	function affected_rows(){
		return mysql_affected_rows();
	}
	function insert_id(){
		return mysql_insert_id();
	}
	function free_result($result){
		return mysql_free_result($result);
	}
	function num_rows($result){
		return mysql_num_rows($result);	
	}
	function close(){
		@mysql_close();
	}
	function db_info(){
		return mysql_get_server_info();
	}
}////////
?>