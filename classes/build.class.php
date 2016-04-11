<?php
	class build {
		static $mess=array();
		static function touch($fileName, $str){
			if(!file_exists($fileName)){
				if(file_put_contents($fileName, $str)){
					self::$mess[]="<?php //创建文件 {$fileName} 成功.?>";
				}
			}
		}
		static function mkdir($dirs){
			foreach($dirs as $dir){
				if(!file_exists($dir)){
					if(mkdir($dir,"0755")){
						self::$mess[]="<?php //创建目录 {$dir} 成功.?>";
					}
				}
			}
		}
		static function create(){
			self::mkdir(array(ROOT."runtime/"));
			$dirs=array(
				ROOT."public",      //系统公共目录
				ROOT."public/uploads/",  //系统公共上传文件目录
				ROOT."public/css/",      //系统公共css目录
				ROOT."public/js/",       //系统公共javascript目录
				ROOT."public/images/",   //系统公共图片目录
				APP_PATH,                   //当前的应用目录
				APP_PATH."css/",         //当前应用的css目录
				APP_PATH."images/",       //当前应用的图片目录
				APP_PATH."js/",       //当前应用的javascript目录
				APP_PATH."modules/"       //当前应用的控制器目录
			);
			self::mkdir($dirs);
				
			$structFile=ROOT."runtime/".str_replace("/","_",APP_PATH).'.LOCK.php';  //主入口文件名
			if(!file_exists($structFile)) {	
				$define_file=INCL.'define.php';
				$fileName=ROOT.APP_PATH."project.ini.php";
				$app_path=ROOT.APP_PATH;
				$app_name=rtrim(APP_PATH,'/');
				$r_path=R_PATH;
				$app_url='http://'.str_replace('//','/',$_SERVER['HTTP_HOST'].'/'.$r_path.$app_name.'/');
				$str=<<<st
<?php
include('{$define_file}');
define('APP_PATH','{$app_path}');
define('CSS_PATH','{$r_path}{$app_name}/css/');
define('IMG_PATH','{$r_path}{$app_name}/images/');
define('JS_PATH','{$r_path}{$app_name}/js/');
define('APP_URL','{$app_url}');
?>
st;
			self::touch($fileName,$str);
			self::touch($structFile, implode("\n", self::$mess));
			}
		}
}
?>