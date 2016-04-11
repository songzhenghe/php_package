<?php
class service {
	protected $sql_func;
	protected $common_func;
	protected $prefix;
	function __construct(){
		global $sql_func;
		global $common_func;
		global $prefix;
		$this->sql_func=$sql_func;
		$this->common_func=$common_func;
		$this->prefix=$prefix;
		isset($this->sql_func) or $this->sql_func=new sql_func;
		isset($this->common_func) or $this->common_func=new common_func;		
	}
}