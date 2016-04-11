<?php
include_once("permission.php");
class myvalidate {
	/**
	 * 表单字段检测服务器端
	 * author:宋正河
	 * date:  2011.11.14 
	 */
	/**
	 * xtrim 除空格函数
	 * string or array $_string
	 * return string or array
	 */
	function xtrim($string){
		if (is_array($string)) {
			foreach ($string as $key => $value) {
				$string[$key] = $this->xtrim($value);  
			}
		} else {
			$string = trim($string);
		}
		return $string;
	}
	/**
	 * _mysql_string 入库前转义
	 * string or array $_string
	 * return string or array
	 * 建议入库前统一过滤
	 */
	function _mysql_string($_string) {
		if (!get_magic_quotes_gpc()) {
			if (is_array($_string)) {
				foreach ($_string as $_key => $_value) {
					$_string[$_key] = $this->_mysql_string($_value);  
				}
			} else {
				$_string = mysql_real_escape_string($_string);
			}
		} 
		return $_string;
	}
	/**
	 * gb_strlen 字符个数统计
	 * string $string
	 * return number
	 */
	function gb_strlen($string){
		if(function_exists('mb_strlen')){
			return mb_strlen($string,"gb2312");
		}
		$j=0;
		$k=0;
		for($i=0;$i<strlen($string);){
			if(ord($string[$i])>127){
				$j++;
				$i+=2;
			}else{
				$k++;
				$i+=1;
			}
		}
		return $j+$k;
	}
	/**
	 * utf8_strlen 字符个数统计
	 * string $string
	 * return number
	 */	
	function utf8_strlen($string) {
		preg_match_all("/./us", $string, $match);
		return count($match[0]);
	}
	
	/**
	 * alert_back 提示跳回函数
	 * string $message
	 * return null
	 */
	function alert_back($message=''){
		echo "<script>alert('{$message}');window.history.back(-1);</script>";
		exit;
	}
	/**
	 * is_required 必填项检测
	 * string $string string $message
	 * return string
	 */
	function is_required($string,$message){
		if(strlen($string)=="0" || !isset($string) || count($string)<1){
			$this->alert_back($message);
		}
		return $string;
	}
	/**
	 * is_notrequired 选填项长度检测
	 * string $string 
	 * return bool
	 */
	function is_notrequired($string){
		if(strlen($string)=="0" || !isset($string) || count($string)<1){
			return false;
		}
		return true;
	}
	/**
	 * check_len 通用项长度检测
	 * string $she_name string $string number $min_length number $max_length array $resource
	 * return string
	 */
	function check_len($she_name,$string,$min_length,$max_length,$resource){
		if($min_length>0){
			$string=$this->is_required($string,$she_name."是必须填写的，不能为空！");
		}
		if($this->gb_strlen($string)<$min_length){
			$this->alert_back("您填写的".$she_name."长度小于".$min_length."位，请重新填写！");
		}
		if($this->gb_strlen($string)>$max_length){
			$this->alert_back("您填写的".$she_name."长度大于".$max_length."位，请重新填写！");
		}
		if($resource){
			$this->alert_back("您填写的".$she_name."已经存在，请使用其它名称填写！");
		}
		return $string;
	}
	/**
	 * check_username 检测用户名函数
	 * string $string number $min_length number $max_length array $resource
	 * return string
	 */
	function check_username($string,$min_length,$max_length,$resource){
		if($min_length>0){
			$string=$this->is_required($string,"用户名是必须填写的，不能为空！");
		}
		if($this->gb_strlen($string)<$min_length){
			$this->alert_back("您填写的用户名长度小于".$min_length."位，请重新填写！");
		}
		if($this->gb_strlen($string)>$max_length){
			$this->alert_back("您填写的用户名长度大于".$max_length."位，请重新填写！");
		}
		if($resource){
			$this->alert_back("您填写的用户名已经存在，请使用其它名称填写！");
		}
		return $string;
	}
	/**
	 * check_password 检测密码函数
	 * string $password string $repassword number $min_length number $max_length
	 * return string
	 */
	function check_password($password,$repassword,$min_length,$max_length){
		if($min_length>0){
			$password=$this->is_required($password,"密码是必须填写的，不能为空！");
		}
		if($this->gb_strlen($password)<$min_length){
			$this->alert_back("您填写的密码长度小于".$min_length."位，请重新填写！");
		}
		if($this->gb_strlen($password)>$max_length){
			$this->alert_back("您填写的密码长度大于".$max_length."位，请重新填写！");
		}
		if($min_length>0){
			$repassword=$this->is_required($repassword,"确认密码是必须填写的，不能为空！");
		}
		if($password!=$repassword){
			$this->alert_back("您填写的两次密码不一致，请重新填写！");
		}
		return $password;
	}
	/**
	 * check_email 检测邮箱格式函数
	 * string $email number $max_length bool $required (true false)
	 * return string
	 */
	function check_email($email,$max_length,$required){
		if($required==true){
			$email=$this->is_required($email,"邮箱是必须填写的，不能为空！");
			if($this->gb_strlen($email)>$max_length){
				$this->alert_back("您填写的邮箱长度大于".$max_length."位，请重新填写！");
			}
			if(!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$email)){
				$this->alert_back("您填写的邮箱格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($email)==true){
				if($this->gb_strlen($email)>$max_length){
					$this->alert_back("您填写的邮箱长度大于".$max_length."位，请重新填写！");
				}
				if(!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$email)){
					$this->alert_back("您填写的邮箱格式不正确，请重新填写！");
				}
			}
		}
		return $email;
	}
	/**
	 * check_tel检测固定电话
	 * string $string bool $required (true false) 
	 * return string
	 */
	function check_tel($string,$required){
		if($required==true){
			$string=$this->is_required($string,"固定电话是必须填写的，不能为空！");
			if(!preg_match("/^\d{3,4}-?\d{7,9}$/",$string)){
				$this->alert_back("您填写的固定电话格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^\d{3,4}-?\d{7,9}$/",$string)){
					$this->alert_back("您填写的固定电话格式不正确，请重新填写！");
				}
			}
		}
		return $string;
	}
	/**
	 * check_mobile检测手机
	 * string $string bool $required (true false) 
	 * return string
	 */
	function check_mobile($string,$required){
		if($required==true){
			$string=$this->is_required($string,"手机号码是必须填写的，不能为空！");
			if(!preg_match("/^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/",$string)){
				$this->alert_back("您填写的手机号码格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/",$string)){
					$this->alert_back("您填写的手机号码格式不正确，请重新填写！");
				}
			}
		}
		return $string;
	}
	/**
	 * check_url检测网址
	 * string $url number $max_length bool $required (true false)
	 * return string
	 */
	function check_url($url,$max_length,$required){
		if($required==true){
			$url=$this->is_required($url,"网址是必须填写的，不能为空！");
			if($this->gb_strlen($url)>$max_length){
				$this->alert_back("您填写的网址长度过长，请重新填写！");
			}
			if(!preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",$url)){
				$this->alert_back("您填写的网址格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($url)==true){
				if($this->gb_strlen($url)>$max_length){
					$this->alert_back("您填写的网址长度过长，请重新填写！");
				}
				if(!preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",$url)){
					$this->alert_back("您填写的网址格式不正确，请重新填写！");
				}
			}
		}
		return $url;
	}
	/**
	 * check_birthday检测出生日期 1990-10-01
	 * string $string bool $required (true false)
	 * return string
	 */
	function check_birthday($string,$required){
		if($required==true){
			$string=$this->is_required($string,"出生日期是必须填写的，不能为空！");
			if(!preg_match("/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/",$string)){
				$this->alert_back("您填写的出生日期格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/",$string)){
					$this->alert_back("您填写的出生日期格式不正确，请重新填写！");
				}
			}
		}
		return $string;	
	}
	/**
	 * check_number检测浮点数字 1 1.5 -2 
	 * string $number number $min number $max number $max_length bool $required (true false)
	 * return number
	 */
	function check_number($number,$min,$max,$max_length,$required){
		if($required==true){
			$number=$this->is_required($number,"数字是必须填写的，不能为空！");
			if($this->gb_strlen($number)>$max_length){
				$this->alert_back("您填写的数字长度过长，请重新填写！");
			}
			if($number<$min || $number>$max){
				$this->alert_back("数字范围应在".$min."至".$max."之间，请重新填写！");
			}
			if(!preg_match("/^(-?\d+)(\.\d+)?$/",$number) || $number=="-0"){
				$this->alert_back("您填写的数字格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($number)==true){
				if($this->gb_strlen($number)>$max_length){
					$this->alert_back("您填写的数字长度过长，请重新填写！");
				}
				if($number<$min || $number>$max){
					$this->alert_back("数字范围应在".$min."至".$max."之间，请重新填写！");
				}
				if(!preg_match("/^(-?\d+)(\.\d+)?$/",$number) || $number=="-0"){
					$this->alert_back("您填写的数字格式不正确，请重新填写！");
				}
			}
		}
		return $number;	
	}
	/**
	 * check_digits检测整型数字 0 1 -2
	 * string $number number $min number $max bool $required (true false)
	 * return number
	 */
	function check_digits($number,$min,$max,$required){
		if($required==true){
			$number=$this->is_required($number,"数字是必须填写的，不能为空！");
			if($number<$min || $number>$max){
				$this->alert_back("数字是整数且范围应在".$min."至".$max."之间，请重新填写！");
			}
			if(!preg_match("/^-?[1-9]\d*$/",$number)){
				$this->alert_back("您填写的数字格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($number)==true){
				if($number<$min || $number>$max){
					$this->alert_back("数字是整数且范围应在".$min."至".$max."之间，请重新填写！");
				}
				if(!preg_match("/^-?[1-9]\d*$/",$number)){
					$this->alert_back("您填写的数字格式不正确，请重新填写！");
				}
			}
		}
		return $number;	
	}
	/**
	 * check_idcard检测身份证号码
	 * string $string bool $required (true false)
	 * return string
	 */
	function check_idcard($string,$required){
		if($required==true){
			$string=$this->is_required($string,"身份证号码是必须填写的，不能为空！");
			if(!preg_match("/^(\d{6})()?(\d{4})(\d{2})(\d{2})(\d{3})(\w)$/",$string)){
				$this->alert_back("您填写的身份证号码格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^(\d{6})()?(\d{4})(\d{2})(\d{2})(\d{3})(\w)$/",$string)){
					$this->alert_back("您填写的身份证号码格式不正确，请重新填写！");
				}
			}
		}
		return $string;	
	}
	/**
	 * check_zipcode检测邮政编码
	 * string $string bool $required (true false)
	 * return number
	 */
	function check_zipcode($string,$required){
		if($required==true){
			$string=$this->is_required($string,"邮政编码是必须填写的，不能为空！");
			if(!preg_match("/^[0-9]{6}$/",$string)){
				$this->alert_back("您填写的邮政编码格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^[0-9]{6}$/",$string)){
					$this->alert_back("您填写的邮政编码格式不正确，请重新填写！");
				}
			}
		}
		return $string;	
	}
	/**
	 * check_ip检测IP
	 * string $string bool $required (true false)
	 * return string
	 */
	function check_ip($string,$required){
		if($required==true){
			$string=$this->is_required($string,"IP地址是必须填写的，不能为空！");
			if(!preg_match("/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/",$string)){
				$this->alert_back("您填写的IP地址格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/",$string)){
					$this->alert_back("您填写的IP地址格式不正确，请重新填写！");
				}
			}
		}
		return $string;	
	}
	/**
	 * check_code 检测验证码函数
	 * string $client string $server 
	 * return null
	 */
	function check_code($client,$server){	
		$client=$this->is_required($client,"验证码是必须填写的，不能为空！");
		if(strtolower($client)!=strtolower($server)){
			$this->alert_back("您填写的验证码不正确，请重新填写！");
		}
	}
	/**
	 * check_qqcode检测qq号码
	 * string $string bool $required (true false)
	 * return number
	 */
	function check_qqcode($string,$required){
		if($required==true){
			$string=$this->is_required($string,"qq号码是必须填写的，不能为空！");
			if(!preg_match("/^[1-9]{1}[\d]{4,9}$/",$string)){
				$this->alert_back("您填写的qq号码格式不正确，请重新填写！");
			}
		}else{
			if($this->is_notrequired($string)==true){
				if(!preg_match("/^[1-9]{1}[\d]{4,9}$/",$string)){
					$this->alert_back("您填写的qq号码格式不正确，请重新填写！");
				}
			}
		}
		return $string;	
	}
	/**
	 * check_file检测文件上传
	 * string $name bool $required (true false)
	 * return string
	 */
	function check_file($name,$required){
		if($required==true){
			$string=$this->is_required($_FILES[$name]["name"],"您还未选择需要上传的文件！");
			
		}else{
			if($this->is_notrequired($_FILES[$name]["name"])==true){
				$string=$_FILES[$name]["name"];
			}
		}
		return $string;	
	}
	/**
	 * 表单字段检测服务器端
	 * author:宋正河
	 * date:  2011.11.14 
	 */
}
?>