<?php
// require_once('cache.class.php');
   // $_GET['module_id']==''?$_GET['module_id']=1:'';
   // $_GET['news_id']==''?$_GET['news_id']=1:'';
   // //需再判断一下参数的值是否符合取值范围
   // $cache = new cache('./html_cache/','1',array('module_id','news_id'));
   // $cache->load(); //装载缓存,缓存有效则不执行以下页面代码

   // //页面代码开始
   // echo date('Y-m-d H:i:s');
   // //页面代码结束
   
   // $cache->write(); //首次运行或缓存过期,生成缓存
   
   // //$cache->clean(); //删除缓存
include_once("permission.php");
class cache {
   /**
    * $dir : 缓存文件存放目录
    * $lifetime : 缓存文件有效期,单位为秒
    * $cacheid : 缓存文件路径,包含文件名
    * $ext : 缓存文件扩展名(可以不用),这里使用是为了查看文件方便
	* $getparam : 有效的get参数数组
   */
   private $dir;
   private $lifetime;
   private $cacheid;
   private $ext;
   public $getparam;
   /**
    * 析构函数,检查缓存目录是否有效,默认赋值
   */
   function __construct($dir='',$lifetime='forever',$getparam='') {
       if ($this->dir_isvalid($dir)) {
           $this->dir = $dir;
           $this->lifetime = $lifetime;
           $this->ext = '.html';
		   $this->getparam= $getparam;
           $this->cacheid = $this->getcacheid();
       }
   }
   /**
    * 检查缓存是否有效
   */
   private function isvalid() {
       if (!file_exists($this->cacheid)) return false;
       if (!(@$mtime = filemtime($this->cacheid))) return false;
       if($this->lifetime!='forever'){if (mktime() - $mtime > $this->lifetime) return false;}
       return true;
   }
   /**
    * 写入缓存
    * $mode == 0 , 以浏览器缓存的方式取得页面内容
    * $mode == 1 , 以直接赋值(通过$content参数接收)的方式取得页面内容
   */
   public function write($mode=0,$content='') {
       switch ($mode) {
           case 0:
               $content = ob_get_contents();
               break;
           default:
               break;
       }
       ob_end_flush();
       try {
		   $fp=fopen($this->cacheid,'wb');
		   flock($fp,2);//加锁
		   fwrite($fp,$content);
		   flock($fp,3);
		   fclose($fp);
           //file_put_contents($this->cacheid,$content);
       }
       catch (Exception $e) {
           $this->error('写入缓存失败!请检查目录权限!');
       }
   }
   /**
    * 加载缓存
    * exit() 载入缓存后终止原页面程序的执行,缓存无效则运行原页面程序生成缓存
    * ob_start() 开启浏览器缓存用于在页面结尾处取得页面内容
   */
   public function load() {
       if ($this->isvalid()) {
           require_once($this->cacheid);
           //echo file_get_contents($this->cacheid);
           exit();
       }
       else {
           ob_start();
       }
   }
   /**
    * 清除缓存
   */
   public function clean() {
       try {
           unlink($this->cacheid);
       }
       catch (Exception $e) {
           $this->error('清除缓存文件失败!请检查目录权限!');
       }
   }
   /**
    * 取得缓存文件路径
   */
   private function getcacheid() {
       return $this->dir.md5($this->geturl()).$this->ext;
   }
   /**
    * 检查目录是否存在或是否可创建
    */
   private function dir_isvalid($dir) {
       if (is_dir($dir)) return true;
       try {
           mkdir($dir,0777);
       }
       catch (Exception $e) {
             $this->error('所设定缓存目录不存在并且创建失败!请检查目录权限!');
             return false;            
       }
       return true;
   }
   /**
    * 取得当前页面完整url
   */
   private function geturl() {
	   if(is_array($this->getparam) && count($this->getparam)>0){
		   $n=array_keys($_GET);
		   for($i=0;$i<count($n);$i++){
			   if(!in_array($n[$i],$this->getparam)){
				unset($_GET[$n[$i]]);
			   }
		   }
	   }
	   ksort($_GET);//保证$url唯一性
       $url = '';
       $url = $_SERVER['PHP_SELF'];
       $url .= (empty($_SERVER['QUERY_STRING']) && $_GET=='')?'':'?'.http_build_query($_GET);
       return $url;
   }
   /**
    * 输出错误信息
   */
   private function error($str) {
       echo '<div style="color:red;">'.$str.'</div>';
   }
}
?>