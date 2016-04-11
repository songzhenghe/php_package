<?php
include_once('permission.php');
class common_func{////////

	//时间函数 调用 $var=$common_func->nowtime();
	function nowtime(){
		$time=time();
		$time=date('Y-m-d H:i:s',$time);
		return $time;
	}
	//时间函数

	//js弹框
	function alert($msg='',$addr=''){
		if($msg!=""){
			echo "<script>alert('{$msg}');</script>";
		}
		if($addr!=""){
			if($addr=='myself'){
				$addr=rtrim($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'],'?');//本页
				echo "<script>location.href='{$addr}';</script>";
				exit;
			}else if($addr=='back'){
				$addr=$_SERVER['HTTP_REFERER'];//前一页
				echo "<script>location.href='{$addr}';</script>";
				exit;
			}else{
				echo "<script>location.href='{$addr}';</script>";
				exit;
			}
			
		}
	}
	//js弹框

	//输出自定义的js
	function js($code){
		echo '<script>';
		echo $code;
		echo '</script>';
	}
	//输出自定义的js

	//参数合并函数
	function param($p){
		if(is_array($p)){
			if(count($p)>0){
				foreach($p as $k=>$v){
					$array[]=$k.'='.$v;
				}
				return '?'.implode('&',$array);
			}else{
				exit('参数个数为空！');	
			}
		}else{
			return '?'.$p;
		}
	}
	//参数合并函数

	/* 
	Utf-8、gb2312都支持的汉字截取函数 
	cut_str(字符串, 截取长度, 开始长度, 编码); 
	编码默认为 utf-8 
	开始长度默认为 0 
	*/
	function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') { 
		if($code == 'UTF-8') { 
		$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
		preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)).""; 
		return join('', array_slice($t_string[0], $start, $sublen)); 
		} 
		else { 
		$start = $start*2; 
		$sublen = $sublen*2; 
		$strlen = strlen($string); 
		$tmpstr = ''; for($i=0; $i<$strlen; $i++) 
		{ 
		if($i>=$start && $i<($start+$sublen)) { 
		if(ord(substr($string, $i, 1))>129) { 
		$tmpstr.= substr($string, $i, 2); 
		} 
		else { 
		$tmpstr.= substr($string, $i, 1); 
		} 
		} 
		if(ord(substr($string, $i, 1))>129) $i++; 
		} 
		if(strlen($tmpstr)<$strlen ) $tmpstr.= ''; 
		return $tmpstr; 
		} 
	}

   /*
	//文件上传函数 调用 $var=$common_func->fileupload(上传路径,文件类型,文件大小,文件域名称);
	function fileupload($path,$type,$size,$fname=''){ 
	$size=$size*1048576;
	$fname=($fname=='')?'file':$fname;
	$filename=basename($_FILES[$fname]['name']);
	$filetype=strtolower(substr($filename,strrpos($filename,'.'),strlen($filename)-strrpos($filename,'.')));
	if(!in_array($filetype,$type)){
	  echo "<script>alert('文件类型错误，不能上传！');history.back(-1);</script>";
	  exit;
	}
	if ($_FILES[$fname]['size']>$size) {
	  echo "<script>alert('文件太大，不能上传！');history.back(-1);</script>";
	  exit;
	}
	$filename=time().rand(0,100000);
	$filename=$filename.$filetype; 
	if($path==''){
	  $savedir=$filename;
	}else{
	  $savedir=$path.$filename;
	}
	if(move_uploaded_file($_FILES[$fname]['tmp_name'],$savedir)){
	  return $filename;
	  exit;
	}else{
	  echo "<script>alert('错误，无法将附件写入服务器!');history.back(-1);</script>";
	  exit;
	}     
	} 
	//文件上传函数
	*/
  
	//去除\
	function stripslashes_array($string) {
		if (is_array($string)) {
			foreach ($string as $k => $v) {
				$string[$k] = $this->stripslashes_array($v);
			}
		} else if (is_string($string)) {
			$string = stripslashes($string);
		}
		return $string;
	}
	//mysql引号字符加\(推荐////////)
	function safe_mysql_string($string) {
		if (!GPC) {
			if (is_array($string)) {
				foreach ($string as $_key => $_value) {
					$string[$_key] = $this->safe_mysql_string($_value);
				}
			} else {
				$string = mysql_real_escape_string(safe_html(trim($string)));
			}
		} 
		return $string;
	}
	//字符转换1：向数据库中插入或更新时用 调用 $var=$common_func->str_to1(字符串);
	function str_to1($str){
		// $str=str_replace(" ","&nbsp;",$str);  
		// $str=str_replace("<","&lt;",$str);  
		// $str=str_replace(">","&gt;",$str); 
		// $str=str_replace("&","&amp;",$str); 
		// $str=str_replace("\"","&quot;",$str); 
		// $str=str_replace("'","&#039;",$str); 
		$str=nl2br(htmlspecialchars($str,ENT_QUOTES));               
		return $str;
	}
	//字符转换1：向数据库中插入或更新时用

	//字符转换2：从数据库中读出显示在表单文本框中用 调用 $var=$common_func->str_to2(字符串);
	function str_to2($str){
		$str=str_replace("&quot;","\"",$str);
		$str=str_replace("&#039","'",$str);
		$str=str_replace("&lt;","<",$str); 
		$str=str_replace("&gt;",">",$str); 
		$str=str_replace("&nbsp;"," ",$str);  
		$str=str_replace("<br />","\n",$str); 
		$str=str_replace("<br>","\n",$str);  
		return $str;
	}
	//字符转换2：从数据库中读出显示在表单文本框中用

	//限制登陆函数 调用 $common_func->restrict(session名字,登陆页地址);
	function restrict($session_name,$login_path){ //函数开始
		if ($_SESSION[$session_name]==""){
		  echo "<script>location.href='".$login_path."';</script>";
		  exit;
		}
	}
	//限制登陆函数
  	//自动处理分页参数
	function auto_qs(){
		$p_url=$_SERVER['QUERY_STRING'];
		parse_str($p_url,$arr);
		if(array_key_exists('page_id',$arr)){
			unset($arr['page_id']);
		}
		if(GPC) $arr=$this->stripslashes_array($arr);
		return $arr;
	}
	//
	/*
	//示例开始
	$query="select * from `abc` order by `id` desc";
	__pages($query);
	if($total_num>0){
	pages();
	echo "<br /><br /><br /><br /><br /><br />";
	$info=$sql_func->mselect($query); 
		for($i=0;$i<count($info);$i++){
			echo $info[$i]["name"];
			echo "<br />";
		}
	}
	//示例结束
	*/
	//预分页函数
	function __pages($sql,$size='',$address='auto'){
		global $C,$sql_func,$total_num,$total_page,$page_id,$add,$pagesize,$query;//全局化变量
		$sql=preg_replace("/([\w\W]*?select)([\w\W]*?)(from[\w\W]*?)/i","$1 count(0) as `total_num` $3",$sql); 
		$info=$sql_func->select($sql);
		$total_num=$info['total_num'];
		$page_id=$_GET['page_id']+0;
		$page_id=($page_id<=0)?'1':$page_id;
		if($address=='auto'){
			$address=$this->auto_qs();
		}
		if(is_array($address)){
			if(count($address)>0){
				foreach($address as $k=>$v){
					$array[]=$k.'='.$v;
				}
				$add='?'.implode('&',$array).'&';
			}else{
				$add='?';
			}
		}
		$pagesize=($size>0)?$size:$C['PAGE_DATA_NUM'];
		$total_page=ceil($total_num/$pagesize);
		$page_id=($page_id>$total_page)?$total_page:$page_id;
		$begin=($page_id-1)*$pagesize;
		$query=$query." limit $begin,$pagesize";
	}
	//预分页函数
	//分页函数 调用 $common_func->pages($type,$nav_num);//$type为类型,$nav_num为分页导航数量
	function pages($type=2,$nav_num=''){ 
		//$total_num为总记录数，$page_id为当前页码，$add为链接地址，$pagesize为新闻条数
		global $C,$total_num,$page_id,$add,$pagesize,$total_page;
		$add=$add.'page_id=';
		$up=$page_id-1;
		if($up<1) $up=1;
		$down=$page_id+1;
		if($down>$total_page) $down=$total_page;
		if($page_id==1){
			echo '&lt;&lt;首页&nbsp;';
		}else{
			$link=$add.'1';
			echo '<a href=' . $link .'>&lt;&lt;首页</a>&nbsp;';
		}
		if($up==1){
			echo "<span style='color:grey;'>&lt;上一页</span>&nbsp;&nbsp;";
		}else {
			$link=$add.$up;
			echo '<a href=' . $link . '>&lt;上一页</a>&nbsp;&nbsp;';
		}
		if($type==1){
			$link=$add;
			echo "<select onchange=\"window.location='" . $link . "'+this.value;\">";
			for($i=1;$i<=$total_page;$i++){
				if($i==$page_id){
					echo "<option value='" . $i . "' selected='selected'>&nbsp;&nbsp;" . $i . '/' . $total_page."&nbsp;&nbsp;</option>";
				}else{
					echo "<option value='" . $i . "'>&nbsp;&nbsp;" . $i . '/' . $total_page . "&nbsp;&nbsp;</option>";
				}
			}
			echo "</select>";
		}else if($type==2){
			//计算页码导航
			$nav = array();
			$nav_num=$nav_num>0?$nav_num:$C['PAGE_NAV_NUM'];
			$nav[0] = '<span>' . $page_id . '</span>';
			for($left = $page_id-1,$right=$page_id+1;($left>=1||$right<=$total_page)&&(count($nav) <= $nav_num);) {
				if($left >= 1) {
					array_unshift($nav,'<a href="' . $add . $left . '">[' . $left . ']</a>');
					$left -= 1;
				}
				if($right <= $total_page) {
					array_push($nav,'<a href="' . $add . $right . '">[' . $right . ']</a>');
					$right += 1;
				}
			}
			echo implode(' ',$nav);
		}
		if($down==$total_page){
			echo "&nbsp;&nbsp;<span style='color:grey;'>下一页&gt;</span>&nbsp;";
		}else {
			$link=$add.$down;
			echo '&nbsp;&nbsp;<a href=' . $link . '>下一页&gt;</a>&nbsp;';
		}
		if($page_id==$total_page){
			echo '尾页&gt;&gt;';
		}else{
			$link=$add.$total_page;
			echo "<a href='" . $link . "'>尾页&gt;&gt;</a>";
		}
		echo '&nbsp;&nbsp;';
		
		echo ' 共' . $total_num . '条记录 每页显示' . $pagesize . '条';
		echo "&nbsp;&nbsp;跳至第<input type='text' id='pagejump' value='" . (($page_id+1)>$total_page?$total_page:($page_id+1)) . "' style='width:20px;text-align:center;' />页
		&nbsp;<input type='button' value='Go!' onclick=\"location.href='" . $add . "'+document.getElementById('pagejump').value\" style='cursor:pointer;' />";
	} 
	//分页函数
    
	//图片缩放函数 调用$common_func->resizeimg(宽,高,路径,文件名,目标,文件名前缀);
	function resizeimg($w,$h,$path,$image,$dest,$pre){
	$img=getimagesize($path.$image);
	$width=$img[0];
	$height=$img[1];
	if($width>$w){
		$x=$w;
		$y=round($x*$height/$width);
	}else{
		$x=$width;
		$y=$height;
	}
	if($y>$h){
		$x=round($x-($y-$h)*$width/$height);
		$y=$h;
	}
	switch($img[2]){
		case 1:
		$im=@imagecreatefromgif($path.$image);
		break;
		case 2:
		$im=@imagecreatefromjpeg($path.$image);
		break;
		case 3:
		$im=@imagecreatefrompng($path.$image);
		break;
	}
	$newimg=imagecreatetruecolor($x,$y);
	imagecopyresized($newimg,$im,0,0,0,0,$x,$y,$width,$height);
	$filename=$dest.$pre.$image;
	switch($img[2]){
		case 1:
		imagegif($newimg,$filename);
		break;
		case 2:
		imagejpeg($newimg,$filename);
		break;
		case 3:
		imagepng($newimg,$filename);
		break;
	}
	}
	//图片缩放函数

	//长度检测 调用$common_func->enter_check(内容,类型,长度,默认值);
	function enter_check($obj,$type,$length,$default=''){
		$obj=trim($obj);
		$obj=$this->safe_mysql_string($obj);
		if($type=="number"){
			if($obj<=0){
				$obj=1;
			}
			if(eregi("^[0-9]+$",$obj)==false and $default!=""){
				$obj=$default;
			}
		}
		if(strlen($obj)==0 and $default!=""){
			$obj=$default;
		}
		if(strlen($obj)>$length){
			$obj=$this->cut_str($obj,$length,0);
		}
		return $obj;
	}
	//长度检测
  
	//二维数组转一维
	function multi2single($array){
		$new_array=array();
		$keys=array_keys($array[0]);
		$size=count($keys);
		for($i=0;$i<count($array);$i++){
			for($j=0;$j<$size;$j++){
				$key_name=$keys[$j];
				$new_array[$key_name][]=$array[$i][$key_name];
			}
		}
		return $new_array;
	}
	//二维数组转一维
	
	//下拉菜单编辑 调用 $common_func->edit_select($array,"2","id","name");
	function edit_select($array,$selected,$id,$name){
		for($i=0;$i<count($array);$i++){
				if($array[$i][$id]==$selected){
					echo "<option value=\"".$array[$i][$id]."\" selected=\"selected\">".$array[$i][$name]."</option>\n";
				}else{
					echo "<option value=\"".$array[$i][$id]."\">".$array[$i][$name]."</option>\n";
				}
		}
	}
	//下拉菜单编辑
  
	//下拉菜单列表 调用 $common_func->choose_select($array,"id","name");
	function choose_select($array,$id,$name){
		for($i=0;$i<count($array);$i++){
					echo "<option value=\"".$array[$i][$id]."\">".$array[$i][$name]."</option>\n";
		}
	}
	//下拉菜单列表
  
}////////
?>