<?php
///数据缓存
// $db_cache=new db_cache(ROOT.'runtime/db_cache',$query,DB_CACHE_TIME);
// $info=$db_cache->action('r');
// if(!$info){
  // $info=$sql_func->select($query); 
  // $db_cache->action('w',$info);
// }
///数据缓存
include_once("permission.php");
class db_cache{

 
private $cache = array();

/**缓存目录||sql语句||有效时间（forever：永远,时间秒数）*/

public function __construct($cacheDir,$sql,$lifeSecond){

$this->cache['cacheDir'] = $cacheDir;

!is_dir($this->cache['cacheDir']) && mkdir($this->cache['cacheDir']);

$this->cache['cacheName'] = md5($sql);

$this->cache['cacheLifeSecond'] = $lifeSecond;

}

public function action($method,$data = ''){

$file = $this->cache['cacheDir'] . '/' . $this->cache['cacheName'] . '.php';

if ($method === 'r') {

if (file_exists($file)) {

if ($this->cache['cacheLifeSecond'] != 'forever') {

if ((filemtime($file) + $this->cache['cacheLifeSecond']) < time()) {

@unlink($file);

return false;

}

}

return require $file;

}

return false;

} else if ($method === 'w') {

$data = '<?php return ' . var_export($data,true) . ';';

return @file_put_contents($file,$data);

} else if($method === 'd'){

@unlink($file);

}

 

}

 

}
?>