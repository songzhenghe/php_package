<?php
include_once("permission.php");
abstract class db {
	public    $link;
	public    $pre;
	protected $definedjs='';
	protected $definedalert='';
	
	public $_pk='id';//在外部设置
	public $_table='';//在外部设置
	
	function __construct(){
		global $prefix;
		$this->pre=$prefix;
	}
	/*抽像区*/
	abstract function connect($host,$username,$password,$database='',$port='');
	abstract function error();
	abstract function select_db($name);
	abstract function query($sql);
	abstract function fetch_array($result);
	abstract function fetch_assoc($result);
	abstract function fetch_row($result);
	abstract function affected_rows();
	abstract function insert_id();
	abstract function free_result($result);
	abstract function num_rows($result);
	abstract function db_info();
	abstract function close();
	/*公共区*/
	public function i3($data){
		$query=$this->ready("insert into `{$this->pre}{$this->_table}` %s",$data);
		return $this->query($query);
	}
	public function i5($data){
		$query=$this->ready("insert into `{$this->pre}{$this->_table}` %s",$data);
		$this->insert($query);
	}
	public function i7($data){
		$query=$this->ready("insert into `{$this->pre}{$this->_table}` %s",$data);
		$this->insert($query,2);
	}
	public function u3($data,$id){
		$query=$this->ready("update `{$this->pre}{$this->_table}` set %s where `{$this->_pk}`='$id' limit 1");
		return $this->query($query);
	}
	public function u5($data,$id){
		$query=$this->ready("update `{$this->pre}{$this->_table}` set %s where `{$this->_pk}`='$id' limit 1");
		$this->update($query);
	}
	public function u7($data,$id){
		$query=$this->ready("update `{$this->pre}{$this->_table}` set %s where `{$this->_pk}`='$id' limit 1");
		$this->update($query,2);
	}
	public function d3($id){
		$query="delete from `{$this->pre}{$this->_table}` where `{$this->_pk}`='$id' limit 1";
		return $this->query($query);
	}
	public function d5($id){
		$query="delete from `{$this->pre}{$this->_table}` where `{$this->_pk}`='$id' limit 1";
		$this->delete($query);
	}
	public function d7($id){
		$query="delete from `{$this->pre}{$this->_table}` where `{$this->_pk}`='$id' limit 1";
		$this->delete($query,2);
	}
	public function all(){
		$query="select * from `{$this->pre}{$this->_table}` order by `{$this->_pk}` desc";
		return $this->mselect($query);
	}
	public function find($id){
		$query="select * from `{$this->pre}{$this->_table}` where `{$this->_pk}`='$id' limit 1";
		return $this->select($query);
	}
	
	//$sql_func->advanced_js('alert::数据修改成功！')->
	//advanced_js('reload::parent')->update($query,2);
	function advanced_js($string){
			//alert::codes
			//reload::myself
			//reload::parent
			//jump::back
			//jump::myself
			//jump::address
			//js::codes
			if($string==''){
				exit('请传入js代码');
			}
			$type=explode('::',$string);
			switch($type[0]){
				case 'alert' :
					$this->definedalert.="<script>alert('{$type[1]}');</script>";
				break;
				case 'reload' :
				if($type[1]=='myself'){
					$this->definedjs.='<script>window.location.reload();</script>';
				}else if($type[1]=='parent'){
					$this->definedjs.='<script>window.parent.location.reload();</script>';
				}
				break;
				case 'jump' :
				if($type[1]=='back'){
					$addr=$_SERVER['HTTP_REFERER'];//前一页
					$this->definedjs.="<script>location.href='{$addr}';</script>";
				}else if($type[1]=='myself'){
					$addr=rtrim($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'],'?');//本页
					$this->definedjs.="<script>location.href='{$addr}';</script>";
				}else{
				    $this->definedjs.="<script>location.href='{$type[1]}';</script>";
				}
				break;
				case 'js' :
					$this->definedjs.="<script>{$type[1]}</script>";
				default:
					
				break;
			}
			return $this;
	}
	//处理sql语句(为insert和update)
	function ready($query,$array=null,$special=array()){
		global $post;
		$_pk=$this->_pk;
		$arr=array();
		if(is_null($array)){
			$array=$post;
		}
		$method=strtolower(substr($query,0,6));
		if($method=='insert'){
			$tablename=preg_replace("/insert into(?:\s)*(?:`)?([a-z0-9_-]+)(?:`)?(?:\s)*.*/is","\\1",$query);
		}else if($method=='update'){
			$tablename=preg_replace("/update(?:\s)*(?:`)?([a-z0-9_-]+)(?:`)?(?:\s)*.*/is","\\1",$query);
		}else{
			exit('方法名只能为：insert 或 update');
		}
		$fieldList=$this->setTable($tablename);
		$n=count($special);
		unset($array[$_pk]);
		foreach($array as $key=>$value){
			$key=strtolower($key);
			if(in_array($key, $fieldList)){//去掉值为空！
				if($n>0 && in_array($key,$special)){
					if($value!=''){
						$arr['`'.$key.'`']=$value;
					}
				}else{
					$arr['`'.$key.'`']=$value;
				}
			}	
		}
		if($method=='insert'){
			$queryvalues='('.implode(',', array_keys($arr)).") values ('".implode("','", array_values($arr)). "')";
		}else if($method=='update'){
			$queryvalues='';
			foreach ($arr as $k=>$v) {
				$queryvalues .="{$k}='{$v}',";
			}
			$queryvalues=rtrim($queryvalues, ',');
		}
		return sprintf($query,$queryvalues);
	}
	//处理sql语句(为insert和update)
	//保存与读取表结构
	function setTable($tabName){
		global $C;
		$cachefile=ROOT.'runtime/table_cache/'.$tabName.'.php';
		if(file_exists($cachefile)){
			$json=ltrim(file_get_contents($cachefile),'<?php //');
			$fieldList=(array)json_decode($json, true);
			if($C['DEBUG']==1){
				debug::addmsg("表<b>{$tabName}</b>结构：".implode(",", $fieldList),2);
			}
			return $fieldList;
		}else{
			$result=$this->query("desc {$tabName}");
			$fields=array();
			while($row=$this->fetch_assoc($result)){
				$fields[]=strtolower($row['Field']);
			}
			file_put_contents($cachefile, '<?php //'.json_encode($fields));
			if($C['DEBUG']==1){
				debug::addmsg("表<b>{$tabName}</b>结构：".implode(',', $fields),2);
			}
			return $fields;
		}
	}
	//保存与读取表结构
	//计算数据库尺寸
	function db_size(){
		$query="show table status";
		$info=$this->mselect($query);
		$size=0;
		for($i=0;$i<count($info);$i++){
			$size+=($info[$i]['Data_length'])+($info[$i]['Index_length']);
		}
		return changeFileSize($size);
	}
	//insert 函数 调用 $sql_func->insert(sql语句[,是否智能]);
	function insert($query,$simple=1){ 
		global $C;
		($C['DEBUG']==1)?(debug::addmsg($query,2)):'';
		if($this->query($query)){
			$msg='数据插入成功！';
			if($simple==1){
				$addr=rtrim($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'],'?');//本页
				echo "<script>alert('".$msg."');</script>";
				echo "<script>location.href='".$addr."';</script>";
				exit;	
			}else{
				if($this->definedalert){
					echo $this->definedalert;
				}else{
					echo "<script>alert('".$msg."');</script>";
				}
				if($this->definedjs){
					echo $this->definedjs;
				}
				return $this->insert_id();
			}
		}else{
		  echo "<script>alert('数据插入失败！请检查sql语句或数据表结构缓存！');window.history.back(-1);</script>";
		  exit;
		}
	} 
	//insert 函数
	//update 函数 调用 $sql_func->update(sql语句[,是否智能]);
	function update($query,$simple=1){
		global $C;
		($C['DEBUG']==1)?(debug::addmsg($query,2)):''; 
		if($this->query($query)){
			$msg='数据修改成功！';
			if($simple==1){
				$addr=rtrim($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'],'?');//本页
				echo "<script>alert('".$msg."');</script>";
				echo "<script>location.href='".$addr."';</script>";
				exit;	
			}else{
				if($this->definedalert){
					echo $this->definedalert;
				}else{
					echo "<script>alert('".$msg."');</script>";
				}		
				if($this->definedjs){
					echo $this->definedjs;
				}
				return true;
			}			
		}else{
		  echo "<script>alert('数据修改失败！请检查sql语句或数据表结构缓存！');window.history.back(-1);</script>";
		  exit;
		}
	} 
	//update 函数
	//delete 函数 调用 $sql_func->delete(sql语句[,是否智能]);
	function delete($query,$simple=1){
		global $C;
		($C['DEBUG']==1)?(debug::addmsg($query,2)):'';  
		if($this->query($query)){
			$msg='数据删除成功！';
			if($simple==1){
				$addr=$_SERVER['HTTP_REFERER'];//前一页	
				echo "<script>alert('".$msg."');</script>";
				echo "<script>location.href='".$addr."';</script>";
				exit;
			}else{
				if($this->definedalert){
					echo $this->definedalert;
				}else{
					echo "<script>alert('".$msg."');</script>";
				}				
				if($this->definedjs){
					echo $this->definedjs;
				}
				return true;
			}
		}else{
		  echo "<script>alert('数据删除失败！可能是SQL语句写错了,请检查！');window.history.back(-1);</script>";
		  exit;
		}
	} 
	//delete 函数
	//select 函数 调用 $info=$sql_func->select(sql语句);
	function select($query){
		global $C;
		($C['DEBUG']==1)?(debug::addmsg($query,2)):'';  
		if($result=$this->query($query)){
		  $info=$this->fetch_array($result,MYSQL_ASSOC);
		  $this->free_result($result);
		  return $info;
		}else{
		  	echo "<script>alert('数据选择失败！可能是SQL语句写错了,请检查！');</script>";
			exit;
		}
	}
	//select 函数
	//mselect 函数 调用 $info=$sql_func->mselect(sql语句);
	function mselect($query){
		global $C;
		($C['DEBUG']==1)?(debug::addmsg($query,2)):'';  
		$infodb=array();
		if($result=$this->query($query)){
		  while($info=$this->fetch_array($result,MYSQL_ASSOC)){
			$infodb[]=$info;
		  }
		  $this->free_result($result);
		  return $infodb;
		}else{
		  	echo "<script>alert('数据选择失败！可能是SQL语句写错了,请检查！');</script>";
			exit;
		}
	}
	//mselect 函数
	//单字段查询 函数
	//select `name` from `test`;
	function get_field($query){
		$result=$this->query($query);
		if($result){
			$info=$this->fetch_row($result);
			if(!empty($info)){
				return $info[0];
			}else{
				return '';
			}
		}else{
			return '';
		}
	}	
	//单字段查询 函数
	//记录总数查询函数 调用 $var=$sql_func->num(sql语句);
	function num($query){
		global $C;
		($C['DEBUG']==1)?(debug::addmsg($query,2)):'';
		if($result=$this->query($query)){
		  $num=$this->num_rows($result);
		  return $num;
		}
	}
	//记录总数查询函数
	//record error sql
	function record_sql($sql){
		return file_put_contents(ROOT.'runtime/error_sql.txt',$sql."\n",FILE_APPEND);
	}
	//
	//id转换成name 调用 $sql_func->id2name($source_id,$target_table_info);
	//$target_table_info="abc::id,user_name::id";
	function id2name($source_id,$target_table_info){
		global $prefix;
		$target_table_infos=explode('::',$target_table_info);
		$field=explode(',',$target_table_infos[1]);
		foreach($field as $v){
			$v=($v=='*')?$v:'`'.$v.'`';
			$fields[]=$v;
		}
		$target_table_infos[1]=implode(",",$fields);
		$query="select {$target_table_infos[1]} from `{$prefix}{$target_table_infos[0]}` where `{$target_table_infos[2]}`='$source_id'";
		$info=$this->select($query);
		return $info;
	}
	//id转换成name 调用 $sql_func->id2name($source_id,$target_table_info);
	//统计子项目数量函数 调用 $sql_func->subcount('表名','字段名','字段值');
	function subcount($table,$key,$value){
		global $prefix;
		$query="select count(*) as `num` from `{$prefix}{$table}` where `{$key}`='{$value}' ";
		$info=$this->select($query);
		return $info['num'];
	}
	//统计子项目数量函数
	//两表关联查询
	//rselect 函数 调用 $info=$sql_func->rselect(
	//array("table"=>"product","fields"=>"id,title,content","glue"=>"id","other"=>"count(id) as one"),
	//array("table"=>"class","fields"=>"up_id,filename,add_time","glue"=>"up_id","other"=>"min(up_id) as two"),
	//"...."
	//);
	//select product.*, class.* from product inner join class on product.pparent=class.id where product.pid is not null
	function rselect($a,$b,$where){
		global $prefix;
		$t1_fields=explode(',',$a['fields']);
		foreach($t1_fields as $v){
			$t1_fields_list[]='t1.'.$v;
		}
		if($a['other']!=''){
			$t1_fields_list[]=$a['other'];
		}
		$t2_fields=explode(',',$b['fields']);
		foreach($t2_fields as $v){
			$t2_fields_list[]='t2.'.$v;
		}
		if($b['other']!=''){
			$t2_fields_list[]=$b['other'];
		}
		$query='select ';
		$query.=implode(',',$t1_fields_list).','.implode(',',$t2_fields_list);
		$query.=" from {$prefix}{$a[table]} t1 inner join {$prefix}{$b[table]} t2 on ";
		$query.='t1.'.$a['glue'].' = '.'t2.'.$b['glue'];
		if($where!=''){
			$query.=' where '.$where;
		}
		return $this->mselect($query);
	}
	//rselect 函数
	//查看表结构 调用$sql_func->desc();
	function desc(){
		global $C;
		$query_d="show tables from {$C['DB_NAME']}";
		$info_d=$this->mselect($query_d);
		$strings="<div style=\"height:200px;overflow:scroll;\">";
		for($j=0;$j<count($info_d);$j++){
			$table=$info_d[$j]["Tables_in_".$C['DB_NAME']];
			$query="desc `{$table}`";
			$info=$this->mselect($query);
			$string="<h3 style='color:green;'>{$table}的数据表结构</h3><ul>";
			for($i=0;$i<count($info);$i++){
				$string.="<li><strong>{$info[$i]['Field']}:{$info[$i]['Type']}</strong> <span style='float:right'>【☆空否：{$info[$i]['Null']}☆键：{$info[$i]['Key']}☆默认：{$info[$i]['Default']}☆额外：{$info[$i]['Extra']}】</span></li>";
			}
			$string.="</ul>";
			$strings.=$string;
		}
		$strings.="</div>";
		($C['DEBUG']==1)?(debug::addmsg($strings,2)):'';  
		
	}
	//查看表结构 调用$sql_func->desc();
	//运行sql语句（多条以;分隔）调用$sql_func->runsql($string);多为建表语句
	function runsql($string){
		$string=rtrim($string,';');
		$sqls=explode(';',$string);
		$ok=0;
		$n=count($sqls);
		for($i=0;$i<$n;$i++){
			if(@$this->query($sqls[$i])){
				$ok+=1;
			}
		}
		if($ok==$n){
			return true;
		}else{
			return false;
		}
	}
	//运行sql语句（多条以;分隔）调用$sql_func->runsql($string);
	//搜索转义
	function strip_search($value){
		$value=addslashes_array($value);
		$value=str_replace("%","\%",$value);
		$value=str_replace("_","\_",$value);
		return $value;
	}
	//查找，替换
	function db_data_replace($table,$field,$search,$replace){
		global $prefix;
		return "update `{$prefix}{$table}` set `{$field}` = REPLACE(`{$field}`,'$search','$replace') where INSTR(`{$field}`,'$search') > 0";
	}
	//通过id进行删除
	function delete_by_ids($table,$id){
		global $prefix;
		if($id>0){
			$query="delete from `{$prefix}{$table}` where `id` = '{$id}'";
		}else if(is_array($id) && count($id)>0){
			$id_string=implode(',',$id);
			$query="delete from `{$prefix}{$table}` where `id` in ({$id_string})";// ('1','2','3')
		}
		return $this->query($query);
	}
	//查找指定id记录
	function select_by_ids($table,$field,$id){
		global $prefix;
		if($field!='*'){
			$field_string='`'.implode('`,`',$field).'`';
		}else{
			$field_string='*';
		}
		if($id>0){
			$query="select {$field_string} from `{$prefix}{$table}` where `id`='$id'";
		}else if(is_array($id) && count($id)>0){
			$id_string=implode(',',$id);
			$query="select * from `{$prefix}{$table}` where `id` in ({$id_string})";
		}
		return $this->mselect($query);
	}
	//数组增、删
	function array_set($source,$in=array(),$out=array()){
		$out=array_merge(array('Submit'),$out);
		foreach($out as $v){
			if(array_key_exists($v,$source)){
				unset($source[$v]);
			}
		}
		return array_merge($source,$in);
	}
	//$source=array('name'=>'张三','age'=>'16','Submit'=>'提交');
	//$in=array('addtime'=>'121234556','tag'=>1);
	//$out=array('age');
	//$arr=array_set($source,$in,$out);
	//print_r($arr);
	
	//数据插入
	function insert_by_array($table,$data,$in=array(),$out=array()){
		$data=$this->array_set($data,$in,$out);
		global $prefix;
		$query="insert into `{$prefix}{$table}` (`".implode('`,`',array_keys($data))."`) values ('".implode("','",array_values($data))."')";
		return $this->query($query);
	}
	//$where=array('id::='=>1);
	//数据更新
	function update_by_array($table,$data,$in=array(),$out=array(),$where){
		$data=$this->array_set($data,$in,$out);
		global $prefix;
		$query="update `{$prefix}{$table}` set ";
		foreach ($data as $k=>$v) {
			$query.="`{$k}`='{$v}',";
		}
		$query=rtrim($query,',');
		$key=array_keys($where);
		$con=explode('::',$key[0]);
		$query=$query." where `{$con[0]}` {$con[1]} {$where[$key[0]]}";
		return $this->query($query);
	}	
}
?>