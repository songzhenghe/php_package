<?php
class enums {
	private $type;
	private $source;
	private $level;
	
	function __construct(){
		//0为预留的空位
		$this->type=array(1=>'个人客户',2=>'企业客户');//客户类型 
		$this->source=array(1=>'网络',2=>'报纸',3=>'展会',4=>'其它');//客户来源
		$this->level=array(1=>'一星',2=>'二星',3=>'三星',4=>'四星',5=>'五星');//客户等级
		
	}	
	function text($n1='',$n2=''){
		$vars=array_keys(get_class_vars(__CLASS__));
		$arr=array();
		foreach($vars as $v){
			$arr[$v]=$this->$v;
		}
		if($n1!='' and $n2!=''){
			return $arr[$n1][$n2];
		}else{
			return $arr;
		}
	}
	
	function get($name){
		 if(is_array($this->$name)){
		 	$opt="<option value=''>--请选择--</option>\n";
		 	foreach($this->$name as $k=>$v){
		 		$opt.="<option value='{$k}'>{$v}</option>\n";
		 	}
			return $opt;
		 }
		 return false;
	}
	function getall(){
		$vars=array_keys(get_class_vars(__CLASS__));
		$arr=array();
		foreach($vars as $v){
			$arr[$v]=$this->get($v);
		}
		return $arr;
	}
	function getall_edit($info){//$info=array('type'=>,'level'=>);
		$vars=array_keys(get_class_vars(__CLASS__));
		$arr=array();
		foreach($vars as $v){
			//判断一下 key是否存在
			if(array_key_exists($v, $info)){
				$arr[$v]=$this->get_edit($v,$info[$v]);
			}
		}
		return $arr;
	}
	function get_edit($name,$index){
		if(is_array($this->$name)){
		 	$opt="<option value=''>--请选择--</option>\n";
		 	foreach($this->$name as $k=>$v){
		 		$sel=($index==$k)?"selected='selected'":'';
		 		$opt.="<option value='{$k}' {$sel} >{$v}</option>\n";
		 	}
			return $opt;
		 }
		 return false;
	}
}