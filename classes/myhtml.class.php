<?php
class myhtml{
	private $_html_1;
	private $_html_2;
	private $_title;
	private $_encoding;
	private $_head;
	private $_meta;
	private $_js;
	private $_css;
	private $_body;
	public function __construct(){
		$this->_html_1=<<<st
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">\r\n
st;
		$this->_html_2="</html>";
		$this->_title="<title>网站内容管理系统</title>\r\n";
		$this->_encoding="utf-8";
		$this->_head_1="<head>\r\n";
		$this->_head_2="</head>\r\n";
		$this->_body="";
		$this->_meta=<<<st
<meta http-equiv="Content-Type" content="text/html; charset={$this->_encoding}" />\r\n
st;
	}
	public function _html($code='',$v=''){
		$this->_encoding=($code=="")?$this->_encoding:$code;		
		$this->_html_1=($v=="")?$this->_html_1:$v;
		$this->_meta=<<<st
<meta http-equiv="Content-Type" content="text/html; charset={$this->_encoding}" />\r\n
st;
		return $this;
	}
	public function _head($type='',$value=array()){
		//使用多个参数
		//$this->_head_v=$v;
		if($type!=''){
			if(!in_array($type,array('title','meta','js','css'))){
				exit('类型错误');
			}
			$type='_'.$type;
			//调用_meta//调用_js//调用_css
			$this->$type($value);
		}
		return $this;
	}
	private function _title($value=array()){
		if(is_array($value)){
			foreach($value as $k=>$v){
				$this->_title=<<<st
<title>{$v}</title>\r\n
st;
			}
		}else{
			$this->_title="<title>{$value}</title>\r\n";
		}
		return $this;	
	}
	private function _meta($value=array()){
		if(is_array($value)){
			foreach($value as $k=>$v){
				$this->_meta.=<<<st
<meta name="{$k}" content="{$v}" />\r\n
st;
			}
		}else{
			$this->_meta.=$value;
		}
		return $this;
	}
	private function _js($value=array()){
		if(is_array($value)){
			foreach($value as $k=>$v){
				$this->_js.=<<<st
<script type="text/javascript" src="{$v}"></script>\r\n
st;
			}
		}else{
			$this->_js.=$value;
		}	
		return $this;
	}
	private function _css($value=array()){
		if(is_array($value)){
			foreach($value as $k=>$v){
				$this->_css.=<<<st
<link href="{$v}" rel="stylesheet" type="text/css" media="screen" />\r\n
st;
			}
		}else{
			$this->_css.=$value;
		}		
		return $this;
	}
	public function _body($v=''){
		$this->_body=($v!="")?"<body>".$v."</body>\r\n":$this->_body;
		return $this;
	}
	
	public function _go(){
		//组成一体并返回
		return $this->_html_1.$this->_head_1.$this->_meta.$this->_js.$this->_css.$this->_title.$this->_head_v.$this->_head_2.$this->_body.$this->_html_2;
	}
	
}
//$myhtml = new myhtml;
//echo $myhtml->_html()->_head('meta',include('meta.php'))->_body(include('aaa.php'))->_go();

//echo $myhtml->_html()->_head('meta',array('keywords'=>'123','description'=>'456'))->_body(include('aaa.php'))->_go();

//echo $myhtml->_html()->_head('js',array('aaa.js'))->_body(include('aaa.php'))->_go();
//echo $myhtml
//->_html()
//->_head('title','这是标题')
//->_head('meta',array('keywords'=>'123','description'=>'456'))
//->_head('css',array('abc.css','def.css'))
//->_head('js',array('aaa.js'))
//->_body(include('aaa.php'))
//->_go();
?>