<?php
//文件上传组件
/**
$config=array(
	'field_name'=>'表单字段名称',
	'upload_path'=>'文件上传后的存放文件夹',
	'allow_type'=>'允许上传的文件类型',//数组
	'max_size'=>'文件最大上传尺寸',
	'unit'=>'文件尺寸单位',
	'israndname'=>'是否随机文件名',
	'iscreatedir'=>'是否自动创建文件夹',
	'iscreatebydate'=>'是否根据日期创建目录',
	'isoverride'=>'文件存在，是否进行覆盖',
);
*/
/**
<?php
header('content-type:text/html;charset=utf-8');
include('upload.instance.php');
if($_POST['submit']){

	$config=array(
		'field_name'=>'myfile',
		'upload_path'=>'E:/wwwroot/instance/uploads/',
		'allow_type'=>array('.jpg','.pdf'),//数组
		'max_size'=>'1',
		'unit'=>'M',
		'israndname'=>false,
		'iscreatedir'=>true,
		'iscreatebydate'=>true,
		'isoverride'=>true
	);

	$r=upload_instance($config);
	echo '<pre>';
	print_r($r);
	echo '</pre>';

}

?>
<form method='post' enctype='multi-part/form-data'>
	<input type='file' name='myfile'/><br />
	<input type='submit' name='submit' />
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000000000000000">
</form>
*/
function upload_instance($config){
	//消息提示
	$message=array(
		0=>'文件上传成功！',
		1=>'上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。',
		2=>'上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。',
		3=>'文件只有部分被上传。',
		4=>'没有文件被上传。',
		6=>'找不到临时文件夹。',
		7=>'文件写入失败。',
		
		-1=>'文件上传失败！',
		-2=>'文件大小超过上传要求！',
		-3=>'文件类型错误！',
		-4=>'文件尺寸单位错误！',
		-5=>'上传文件夹目录不存在！',
		-6=>'创建文件夹失败！',
		-7=>'上传的临时文件不存在！',
		-8=>'要上传的文件已经存在！'
		
	);
	//初始化返回值
	$return=array(
		'origin_name'=>'',
		'new_name'=>'',
		'file_type'=>'',
		'file_size'=>'',
		'file_abspath'=>'',
		'file_relpath'=>'',
		'error_number'=>'',
		'error_message'=>''
		
	);
	
	$file=$_FILES[$config['field_name']];
	$file_mime=$file['type'];
	$mime_array=get_mime($config['allow_type']);
	//检测文件mime类型
	if(!in_array($file_mime,$mime_array)){
		$return['error_number']=-3;
		$return['error_message']=$message[-3];
		return $return;
	}
	//检测自带错误
	if($file['error'] > 0) {
		$return['error_number']=$file['error'];
		$return['error_message']=$message[$file['error']];
		return $return;
	}
	$config['unit']=strtoupper($config['unit']);
	if(!in_array($config['unit'],array('B','K','M','G','T'))){
		$return['error_number']=-4;
		$return['error_message']=$message[-4];
		return $return;
	}
	
	//检测文件尺寸2
	$file_size=$file['size'];
	switch($config['unit']){
		case 'B':
		
		break;
		case 'K':
		$config['max_size']*=1024;
		break;
		case 'M':
		$config['max_size']*=1048576;
		break;
		case 'G':
		$config['max_size']*=1073741824;
		break;
		case 'T':
		$config['max_size']*=1099511627776;
		break;
		
	}
	if($file_size>$config['max_size']){
		$return['error_number']=-2;
		$return['error_message']=$message[-2];
		return $return;
	}
	//生成文件夹名
	if($config['iscreatebydate']){
		$p=date('Y/m/d').'/';
		$config['upload_path']=$config['upload_path'].$p;
		$return['file_relpath']=$p;
	}
	
	//检测上传路径
	$config['upload_path']=rtrim($config['upload_path'],'/').'/';
	if(!$config['iscreatedir']){
		if(!file_exists($config['upload_path'])){
			$return['error_number']=-5;
			$return['error_message']=$message[-5];
			return $return;
		}		
	}
	
	//创建上传文件夹目录
	if($config['iscreatedir']){
		if(!mk_dir($config['upload_path'])){
			$return['error_number']=-6;
			$return['error_message']=$message[-6];
			return $return;
		}
	}
	$file_name=basename($file['name']);//文件名
	//生成新文件名
	$file_type=strrchr($file_name,'.');//文件后缀 .txt
	if($config['israndname']){
		$return['new_name']=time().rand(1000,9999).$file_type;
	}else{
		$return['new_name']=$file_name;
	}
	if(!is_uploaded_file($file['tmp_name'])){
		$return['error_number']=-7;
		$return['error_message']=$message[-7];
		return $return;
	}
	//文件是否存在
	if(!$config['isoverride']){
		if(file_exists($config['upload_path'].$return['new_name'])){
			$return['error_number']=-8;
			$return['error_message']=$message[-8];
			return $return;
		}
	}
	//正式上传
	if(!@move_uploaded_file($file['tmp_name'],$config['upload_path'].$return['new_name'])){
		$return['error_number']=-1;
		$return['error_message']=$message[-1];
		return $return;
	}
	
	//整合返回值
	$return['origin_name']=$file_name;
	$return['file_type']=$file_type;
	$return['file_size']=$file_size;
	$return['file_abspath']=$config['upload_path'];
	$return['error_number']=0;
	$return['error_message']=$message[0];
	return $return;
}

//文件的mime类型
//$array=array('.txt','.doc','.jpg','.png','.rar');
//$arr=array('image/gif','image/jpg');
function get_mime($array){
	if(!is_array($array) or empty($array)) exit;
	$mime_map=array(
		'.gif'=>array('image/gif'),
		'.png'=>array('image/png','image/x-png'),
		'.jpg'=>array('image/jpg','image/jpeg'),
		'.pdf'=>array('application/pdf')
	);
	$arr=array();
	foreach($array as $v){
		$arr=array_merge($arr,$mime_map[$v]);
	}
	return $arr;
}
/*
function mk_dir($target){
	$target=str_replace('//','/',$target); 
	if(file_exists($target)) 
		return @is_dir($target); 
	if(@mkdir($target)){ 
		$stat=@stat(dirname($target)); 
		$dir_perms=$stat['mode'] & 0007777;
		@chmod($target,$dir_perms); 
		return true; 
	}else if(is_dir(dirname($target))){ 
		return false; 
	} 
	if (($target!='/') && (mk_dir(dirname($target)))) 
		return mk_dir($target); 
	return false;
}
*/