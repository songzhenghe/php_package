<?php
/*

$config=array(
	'src'=>'源文件',
	'maxx'=>'长',
	'maxy'=>'宽',
	'dest'=>'目标文件目录（为空则表示保存在源文件夹下）',
	'pre'=>'前缀',
	'isheader'=>'是否浏览器输出',
	'isoverride'=>'是否进行覆盖'
);

*/
/**
<?php
include('pic_resize.instance.php');
$config=array(
	'src'=>'11.jpg',
	'maxx'=>'200',
	'maxy'=>'300',
	'dest'=>'',
	'pre'=>'m_',
	'isheader'=>false,
	'isoverride'=>false
);
$r=pic_resize_instance($config);
echo '<pre>';
print_r($r);
echo '</pre>';
*/
function pic_resize_instance($config){
	if($config['isheader']){
		header('content-type:image/png');
	}
	//获取图片的基本信息
	$info = getimagesize($config['src']);
	$w = $info[0];//获取宽度
	$h = $info[1];//获取高度
	//获取图片的类型并为此创建对应图片资源	
	switch($info[2]){
		case 1: //gif
			$im = imagecreatefromgif($config['src']);
		break;
		case 2: //jpg
			$im = imagecreatefromjpeg($config['src']);
		break;
		case 3: //png
			$im = imagecreatefrompng($config['src']);
		break;
		default:
			die('图片类型错误！');
		break;
	}
	//计算缩放比例
	if(($config['maxx']/$w)>($config['maxy']/$h)){
		$b = $config['maxy']/$h;
	}else{
		$b = $config['maxx']/$w;
	}
	//计算出缩放后的尺寸
	$nw = floor($w*$b);
	$nh = floor($h*$b);
	//创建一个新的图像源(目标图像)
	$nim = imagecreatetruecolor($nw,$nh);
	//执行等比缩放
	imagecopyresampled($nim,$im,0,0,0,0,$nw,$nh,$w,$h);
	if($config['isheader']){
		imagepng($nim);
		//释放图片资源
		imagedestroy($im);
		imagedestroy($nim);
	}else{
		//解析源图像的名字和路径信息
		$picinfo = pathinfo($config['src']);
		$newpicname=$config['pre'].$picinfo['basename'];
		if($config['dest']){
			$config['dest']=rtrim($config['dest'],'/').'/';
			$fullnewpicname= $config['dest'].$newpicname;
		}else{
			$fullnewpicname= $picinfo['dirname'].'/'.$newpicname;
		}
		if(file_exists($fullnewpicname) && $config['isoverride']==false){
			//释放图片资源
			imagedestroy($im);
			imagedestroy($nim);
			return array('number'=>-1,'newpicname'=>$newpicname,'fullnewpicname'=>realpath($fullnewpicname));
		}
		switch($info[2]){
			case 1:
				imagegif($nim,$fullnewpicname);
				break;
			case 2:
				imagejpeg($nim,$fullnewpicname);
				break;
			case 3:
				imagepng($nim,$fullnewpicname);
				break;
		}
		//释放图片资源
		imagedestroy($im);
		imagedestroy($nim);
		//返回结果
		return array('number'=>1,'newpicname'=>$newpicname,'fullnewpicname'=>realpath($fullnewpicname));
	}
}