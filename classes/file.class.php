<?php
	// touch("./php.apahce"); 

	// unlink("C:/AppServ/www/xsphp/apache.php");

	// rename("./test.txt", "d:/test2.txt");

	// copy("cache.txt", "./cache5.txt");

	// chmod("/aaa/index.php", "755");

	class file{
		//读文件
		function read($fileName){
			$file=fopen($fileName, "r");
			if(flock($file, LOCK_SH)){
				$con=fread($file, filesize($fileName));
				flock($file, LOCK_UN);
			}
			fclose($file);
			return $con;
		}
		//读文件
		
		//写文件
		function write($fileName, $mess){
			$file=fopen($fileName, "a");
			if(flock($file, LOCK_EX)){	
				fwrite($file, $mess);
				flock($file, LOCK_UN);
			}
			fclose($file);
			return true;
		}
		//写文件
		
		//转换文件大小
		function toSize($size){
			$dw="Bytes";
			if($size > pow(2, 30)){
				$size=round($size/pow(2, 30), 2);
				$dw="GB";
			}else if($size > pow(2, 20)){
				$size=round($size/pow(2, 20), 2);
				$dw="MB";
			}else if($size > pow(2, 10)){
				$size=round($size/pow(2, 10), 2);
				$dw="KB";
			}else{ 
				$dw="bytes";
			}
			return $size.$dw;
		}
		//转换文件大小
		
		//获得目录大小
		function dirsize($dirname) {
			$dirsize=0;
			$dir=opendir($dirname);
			while($filename=readdir($dir)){
				$file=$dirname."/".$filename;
				if($filename!="." && $filename!=".."){
					if(is_dir($file)){
						$dirsize+=$this->dirsize($file);
					}else{
						$dirsize+=filesize($file);
					}
				}
			}
			closedir($dir);
			return $dirsize;
		}
		//获得目录大小
		
		//目录遍历
		function lookdir($dirname){
			$dir=opendir($dirname);
			$array=array();
			while($fileName=readdir($dir)){
				$file=$dirname.'/'.$fileName;
				if($fileName!="." && $fileName!=".."){
					if(is_dir($file)){
						$array[]=array("filename"=>$fileName,"date"=>date("Y-m-d H:i:s", filectime($file)),"type"=>filetype($file),"size"=>$this->toSize($this->dirsize($file)),"isdir"=>1);
					}else{
						$array[]=array("filename"=>$fileName,"date"=>date("Y-m-d H:i:s", filectime($file)),"type"=>filetype($file),"size"=>$this->toSize($this->dirsize($file)),"isdir"=>0);
					}
				}
			}
			closedir($dir);
			return $array;
		}
		//目录遍历
		
		//mkdir(); //创建一个空目录
		//rmdir(); //只可以删除空目录
		//rename('c:/bbbccc', 'phpMyAdmin');  //和文件操作一样
		
		//目录复制
		function copydir($dirsrc, $dirto){
			if(is_file($dirto)){
				echo "目标不是目录不能创建";
				return;
			}
			if(!file_exists($dirto)){
				mkdir($dirto); 
			}
			$dir=opendir($dirsrc);
			while($filename=readdir($dir)){
				if($filename!="." && $filename!=".."){
					$file1=$dirsrc."/".$filename;
					$file2=$dirto."/".$filename;
					if(is_dir($file1)){
						$this->copydir($file1, $file2); 
					}else{
						copy($file1, $file2);
					}
				}
			}
			closedir($dir);
			return true;
		}
		//目录复制

		//目录删除
		function deldir($dirname){
			if(file_exists($dirname)) {
				$dir=opendir($dirname);
				while($filename=readdir($dir)){
					if($filename!="." && $filename!=".."){
						$file=$dirname."/".$filename;
						if(is_dir($file)){
							$this->deldir($file);
						}else{
							unlink($file);
						}
					}
				}
				closedir($dir);
				rmdir($dirname);
			}
			return true;
		}
		//目录删除
		
		//创建级联目录
		//$target="test/test/test/";
		function create_dirs( $target ) { 
			$target = str_replace( '//', '/', $target ); 
			if ( file_exists( $target ) ) 
				return @is_dir( $target ); 
			if ( @mkdir( $target ) ) { 
				$stat = @stat( dirname( $target ) ); 
				$dir_perms = $stat['mode'] & 0007777;
				@chmod( $target, $dir_perms ); 
				return true; 
			} elseif ( is_dir( dirname( $target ) ) ) { 
					return false; 
			} 
			if ( ( $target != '/' ) && ( $this->create_dirs( dirname( $target ) ) ) ) 
				return $this->create_dirs( $target ); 

			return false; 
		}
		//创建级联目录
	}
?>