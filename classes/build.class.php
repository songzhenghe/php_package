<?php
	class build {
		static $mess=array();
		static function touch($fileName, $str){
			if(!file_exists($fileName)){
				if(file_put_contents($fileName, $str)){
					self::$mess[]="<?php //�����ļ� {$fileName} �ɹ�.?>";
				}
			}
		}
		static function mkdir($dirs){
			foreach($dirs as $dir){
				if(!file_exists($dir)){
					if(mkdir($dir,"0755")){
						self::$mess[]="<?php //����Ŀ¼ {$dir} �ɹ�.?>";
					}
				}
			}
		}
		static function create(){
			self::mkdir(array(ROOT."runtime/"));
			$dirs=array(
				ROOT."public",      //ϵͳ����Ŀ¼
				ROOT."public/uploads/",  //ϵͳ�����ϴ��ļ�Ŀ¼
				ROOT."public/css/",      //ϵͳ����cssĿ¼
				ROOT."public/js/",       //ϵͳ����javascriptĿ¼
				ROOT."public/images/",   //ϵͳ����ͼƬĿ¼
				APP_PATH,                   //��ǰ��Ӧ��Ŀ¼
				APP_PATH."css/",         //��ǰӦ�õ�cssĿ¼
				APP_PATH."images/",       //��ǰӦ�õ�ͼƬĿ¼
				APP_PATH."js/",       //��ǰӦ�õ�javascriptĿ¼
				APP_PATH."modules/"       //��ǰӦ�õĿ�����Ŀ¼
			);
			self::mkdir($dirs);
				
			$structFile=ROOT."runtime/".str_replace("/","_",APP_PATH).'.LOCK.php';  //������ļ���
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