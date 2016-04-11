<?php
class controller extends common_func{
	function index(){
		htmlob_begin();
		//echo "这里是控制器的index();";
		//$this->alert("common_func的alert()方法");
		//echo load_model()->index();
		//echo load_model('test')->index();
		load_viewer()->index();
		//load_public('c')->index();
		//load_public('m')->index();
		//load_public('v')->index();
		$con=htmlob_finish();
		
		$myhtml = new myhtml;
		echo $myhtml
		->_html()
		->_head('title','这是标题')
		->_head('meta',array('keywords'=>'123','description'=>'456'))
		->_head('css',array('abc.css','def.css'))
		->_head('js',array('aaa.js'))
		->_head('js',return_include('example/m.js.php'))
		->_body($con)
		->_go();

		
	}
	function reg($name='',$age=''){
		p($_GET);
	}
	
}
?>