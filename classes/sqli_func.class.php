<?php 
include_once("permission.php");
class sqli_func extends db{////////
	//数据库连接句柄 
	//数据库连接
	public function __construct(){ 
		parent::__construct();
		global $C; 
		$link=$this->connect($C['DB_HOST'], $C['DB_USER'], $C['DB_PWD'] ,$C['DB_NAME'],$C['DB_PORT']) or exit('数据库连接错误！'.$this->error()); 
		$this->link=$link;
		$this->query("SET NAMES {$C['DB_CHAR']}"); 
		
		if($C['DEBUG']==1){
			//$this->desc();
		}
	}
	//数据库连接

	function connect($host,$username,$password,$database='',$port=''){
		return mysqli_connect($host,$username,$password,$database,$port);	
	}
	function error(){
		return mysqli_error($this->link);	
	}
	function select_db($name){
		
	}
	function query($sql){
		global $C;
		$r=mysqli_query($this->link,$sql);
		if($r==FALSE and $C['RECORD_SQL']==TRUE){
			$this->record_sql($sql);
			echo '<br />'.$this->error().'<br />';
			exit;
		}
		return $r;
	}
	function fetch_array($result){
		return mysqli_fetch_array($result,MYSQL_ASSOC);
	}
	function fetch_assoc($result){
		return mysqli_fetch_assoc($result);
	}
	function fetch_row($result){
		return mysqli_fetch_row($result);
	}
	function affected_rows(){
		return mysqli_affected_rows($this->link);
	}
	function insert_id(){
		return mysqli_insert_id($this->link);
	}
	function free_result($result){
		return mysqli_free_result($result);
	}
	function num_rows($result){
		return mysqli_num_rows($result);	
	}
	function close(){
		@mysqli_close($this->link);
	}
	function db_info(){
		return mysqli_get_server_info($this->link);
	}
}////////
?>