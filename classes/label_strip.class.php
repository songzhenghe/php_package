<?php
class label_strip{
	private $filepath=null;
	public  $file_subdir=null;
	private $myLabels=array();
	private $leftDeli='';
	private $rightDeli='';
	public  $file_name='';
	public  $file_content=null;
	public  $file_size=0;
	function __construct($filepath,$left='{-',$right='-}'){
		$this->filepath=rtrim($filepath,'/').'/';
		$this->leftDeli=$left;
		$this->rightDeli=$right;
		$this->myLabels=array_merge($this->init_array(),$this->myLabels);
	}
	private function init_array(){
		$self=array('timer'=>date('Y-m-d H:i:s'));
		$keys=array_keys($self);
		$tmp_arr=array();
		foreach($keys as $v){
			$tmp_arr[$this->leftDeli.$v.$this->rightDeli]=$self[$v];
		}
		return $tmp_arr;
	}
	public function add_label($item,$val){
		$key=$this->leftDeli.$item.$this->rightDeli;
		$this->myLabels[$key]=$val;
		return $this;
	}
	public function replace_label($file){
		$content=file_get_contents($file);
		$this->file_content=str_replace(array_keys($this->myLabels),array_values($this->myLabels), $content);
		return $this;
	}
	public function create_html($name,$myfolder=''){
		if($myfolder==''){
			$subdir=date('Y-m-d').'/';
		}else{
			$subdir=rtrim($myfolder,'/').'/';
		}
		$fulldir=$this->filepath.$subdir;
		if(!file_exists($fulldir)){
			mkdir($fulldir,'0755');
		}
		$filename=$name.'.html';
		$this->file_subdir=$subdir;
		$this->file_name=$filename;
		$this->file_size=file_put_contents($this->filepath.$subdir.$filename,$this->file_content,LOCK_EX);
		return true;
	}
}
// $label=new label_strip('./html');
// $label->add_label('pager','我是分页')->add_label('title','我是标题')->replace_label('./tpl/index.html')->create_html('1','2012-08-13');
// echo $label->file_subdir;
// echo "<br />";
// echo $label->file_name;
// echo "<br />";
// echo $label->file_content;
// echo "<br />";
// echo $label->file_size;
?>