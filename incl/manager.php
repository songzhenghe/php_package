<?php
define('PERMISSION','/');
include('define.php');
if($C['DEBUG']==0 or $C['MGR']==0){
	exit;
}
if($_GET['cleartablecache']=='1'){
	//遍历删除
	$path=ROOT."runtime/table_cache/";
	$dir=opendir($path);
	while($fileName=readdir($dir)){
		if($fileName!="." && $fileName!=".."){
			@unlink($path.$fileName);
		}
	}
	closedir($dir);
	echo "清空完成!<br />";

}
if($_GET['clearhtmlcache']=='1'){
	//遍历删除
	$path=ROOT."runtime/html_cache/";
	$dir=opendir($path);
	while($fileName=readdir($dir)){
		if($fileName!="." && $fileName!=".."){
			@unlink($path.$fileName);
		}
	}
	closedir($dir);
	echo "清空完成!<br />";

}
if($_GET['cleardbcache']=='1'){
	//遍历删除
	$path=ROOT."runtime/db_cache/";
	$dir=opendir($path);
	while($fileName=readdir($dir)){
		if($fileName!="." && $fileName!=".."){
			@unlink($path.$fileName);
		}
	}
	closedir($dir);
	echo "清空完成!<br />";

}
if($_POST["Submit_1"]){
	$folder=$post["folder"];
	if($folder!=""){
		if(file_exists(ROOT."modules/".$folder)){
			echo "文件夹已存在！<br />";
		}else{
			mkdir(ROOT."modules/".$folder);
			echo "<script>location.href='manager.php?selectedfolder=".$folder."';</script>";
			exit;
		}
	}
}
if($_POST["Submit_2"]){
	$file1=$post["file1"];
	$file2=$post["file2"];
	$file3=$post["file3"];
	$destfolder=$post["destfolder"];
	if($file1+$file2+$file3>0){
		if($destfolder!=""){
			//判断文件是否已存在
			$dir=ROOT."modules/".$destfolder."/";
			$num=0;
			if($file1 and !file_exists($dir.$destfolder."_add.php")){
				@copy(ROOT."example/sample_add.php",$dir.$destfolder."_add.php");
				$num+=1;
			}
			if($file2 and !file_exists($dir.$destfolder."_edit.php")){
				@copy(ROOT."example/sample_edit.php",$dir.$destfolder."_edit.php");
				$num+=1;
			}
			if($file3 and !file_exists($dir.$destfolder."_list.php")){
				@copy(ROOT."example/sample_list.php",$dir.$destfolder."_list.php");
				$num+=1;
			}
			echo "成功复制{$num}个文件!请放心文件不会被覆盖！<br />";
		}else{
			echo "目标文件夹还没选择！<br />";
		}
	}else{
		echo "请选择一个sample文件！<br />";
	}
}
?>
<div style="width:500px;margin:50px auto 0 auto;text-align:left;background:#eee;border:2px dashed #000;padding:5px;">
<a href="manager.php">刷新一下</a>
<h2>缓存部分</h2>
<a href="?cleartablecache=1">清空数据表缓存</a>

<a href="?clearhtmlcache=1">清空html缓存</a>

<a href="?cleardbcache=1">清空db数组缓存</a>
<br /><br />
<h2>模块部分</h2>
<form name="form1" method="post">
新建文件夹：<input type="text" value="" name="folder" /> <input type="submit" value="创建" name="Submit_1" />
</form>
<br /><br />
<form name="form1" method="post">
复制sample文件：<br /><br />
<label for='a'>&nbsp;sample_add.php:<input type="checkbox" id='a' value="1" name="file1" checked /></label><br />
<label for='b'>sample_edit.php:<input type="checkbox" id='b' value="1" name="file2" checked /></label><br />
<label for='c'>sample_list.php:<input type="checkbox" id='c' value="1" name="file3" checked /></label>
<br /><br />
到哪里？<br /><br />
<select name="destfolder">
<option value="">选一个吧</option>
<?php
	$path=ROOT."modules/";
	$dir=opendir($path);
	while($fileName=readdir($dir)){
		if($fileName!="." && $fileName!=".."){
			if($get["selectedfolder"]==$fileName){
				$sel="selected";
			}
			echo "<option value=\"".$fileName."\" {$sel}>".$fileName."</option>";
		}
	}
	closedir($dir);
?>
</select>
<input type="submit" value="复制" name="Submit_2" />
</form>
</div>



