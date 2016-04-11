<?php
include_once('permission.php');
	class validate {
		static $data;
		static $action;
		static $msg;
		static $flag=true;
		static $db=null;
		static $tablename;
		static $id;
		/**
		 * 获取XML内标记的属性，并处理回调内部方法
		 * @param	resource	$xml_parser	XML的资源
		 * @param	string		$tagName	数据表的名称
		 * @param	array		$args		XML标记的属性		
		 */
		static function start($xml_parser, $tagName, $args){
			if(isset($args['NAME']) && isset($args['MSG'])) {
				if(empty($args['ACTION']) || $args['ACTION']=='both' || $args['ACTION']==self::$action) {
					if(is_array(self::$data)) {
						if (array_key_exists($args['NAME'],self::$data)) {
							if(empty($args['TYPE'])){
								$method='regex';
							}else{
								$method=strtolower($args['TYPE']);
							}
						
							if(in_array($method, get_class_methods(__CLASS__))){
								//这里开始分配函数进行处理
								//处理前先判断一下有没有nullisok属性

								if(self::$data[$args['NAME']]==''){
									if($args['NULLISOK']=='1'){
										//不验证
									}else{
										self::$method(self::$data[$args['NAME']],$args['MSG'],$args['VALUE'],$args['NAME']);
									}
								}else{
									self::$method(self::$data[$args['NAME']],$args['MSG'],$args['VALUE'],$args['NAME']);
								}
								
							}else{
								self::$msg[]="验证的规则{$args["TYPE"]} 不存在，请检查！<br>";
								self::$flag=false;
							}
					
				
						}else{
							self::$msg[]="验证的字段 {$args["NAME"]} 和表单中的输出域名称不对应<br>";
							self::$flag=false;
						}
					}
				}
			}
		
		}

		static function end($xml_parser, $tagName){
			return true;
		}	

		/**
		 * 解析XML文件
		 * @param	string	$file	XML的文件名
		 * @param	mixed	$data		表单中输出的数据
		 * @param	string	$action		用户执行的操作add或mod,默认为both
		 * @param	object	$db		数据表的连接对象
		 * @param	array	$id		数据修改时防止重复的id
		 */
		static function check($file,$data,$action,$id=array()){
			self::$id=$id;
			//$file=substr($db->tabName, strlen(TABPREFIX));
			global $prefix,$sql_func;
			self::$tablename=$prefix.$file;
			$xmlfile=ROOT.'rules/'.$file.'.xml';
			if(file_exists($xmlfile)) {
				self::$data=$data;
				self::$action=$action;
				self::$db=$sql_func;//引用全局
		
				if(is_array($data) && array_key_exists('verifycode', $data)){
					self::vcode($data['verifycode'], "验证码输入<font color='red'>".$data["verifycode"]."</font>错误！");
				}

				//创建XML解析器
				$xml_parser = xml_parser_create("utf-8");

				//使用大小写折叠来保证能在元素数组中找到这些元素名称
				xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
				xml_set_element_handler ($xml_parser, array(__CLASS__,'start'),array(__CLASS__, 'end'));
				//读取XML文件
				if (!($fp = fopen($xmlfile, 'r'))) {
	    				die("无法读取XML文件$xmlfile");
				}

				//解析XML文件
				$has_error = false;			//标志位
				while ($data = fread($fp, 4096)) {
					//循环地读入XML文档，只到文档的EOF，同时停止解析
					if (!xml_parse($xml_parser, $data, feof($fp)))
					{
						$has_error = true;
						break;
					}
				}

				if($has_error) { 
					//输出错误行，列及其错误信息
					$error_line   = xml_get_current_line_number($xml_parser);
					$error_row   = xml_get_current_column_number($xml_parser);
					$error_string = xml_error_string(xml_get_error_code($xml_parser));

					$message = sprintf("XML文件 {$xmlfile}［第%d行，%d列］有误：%s", 
						$error_line,
						$error_row,
						$error_string);
						self::$msg[]= $message;
						self::$flag=false;
				}
				//关闭XML解析器指针，释放资源
				xml_parser_free($xml_parser);
				return self::$flag;
			}else{
				return true;
			}
				
		}
		/**
		 * 使用自定义的正则表达式进行验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 * @param	string	$rules	正则表达式
		 */ 
		static function regex($value, $msg,$rules) {
			if(!preg_match($rules, $value)) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 * 唯一性验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 * @param	string	$name	需要验证的字段名称
		 */ 
		static function unique($value,  $msg, $rules, $name) {
			$id=self::$id;//将id也带过来
			$where=' where 1=1 ';
			if(count($id)>0){
				foreach($id as $k=>$v){
					$arrtmp=explode('::',$k);
					$where.=" and `{$arrtmp[0]}` {$arrtmp[1]} '$v' ";//$id格式 array('catid::='=>2,'id::!='=>100)
				}
			}
			$tablename=self::$tablename;
			$query="select count(*) as `nums` from `$tablename` $where  and `$name`='$value'";
			$info=self::$db->mselect($query);
			if($info[0]['nums']>0){
				self::$msg[]=$msg;
				self::$flag=false;
			} 
		}
		/**
		 *非空验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 */ 
		static function notnull($value,  $msg) {
			if(strlen(trim($value))==0) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 *Email格式验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 */ 
		static function email($value, $msg) {
			$rules= "/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";

			if(!preg_match($rules, $value)) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 *URL格式验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 */ 
		static function url($value, $msg) {

			$rules='/^http\:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?$/';
			if(!preg_match($rules, $value)) {
				self::$msg[]=$msg;
				self::$flag=false;
			}

		}
		/**
		 *数字格式验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 */ 
		static function number($value, $msg) {
		
			$rules='/^\d+$/';
			if(!preg_match($rules, $value)) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 * 货币格式验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 */ 
		static function currency($value, $msg) {
		
			$rules='/^\d+(\.\d+)?$/';
			if(!preg_match($rules, $value)) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 *验证码自动验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 */ 
		static function vcode($value, $msg){		
			if(strtoupper($value)!=$_SESSION['verifycode']) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 *使用回调用函数进行验证
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 * @param	string	$rules	回调函数名称，回调用函数写在指定的类中可放在incl或classes目录下
		 */ 
		static function callback($value, $msg, $rules) {
			$arrtmp=explode('::',$rules);
			if(!call_user_func_array(array(new $arrtmp[0],$arrtmp[1]), array($value))) {
				self::$msg[]=$msg;
				self::$flag=false;
			}
		}
		/**
		 *验证两次输出是否一致
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 * @param	string	$rules	对应的另一个表单名称
		 */ 
		static function confirm($value, $msg, $rules) {
			if($value!=self::$data[$rules]){
				self::$msg[]=$msg;
				self::$flag=false;
			}	
		}

		/**
		 * 验证数据的值是否在一定的范围内
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 * @param	string	$rules	一个值或多个值，或一个范围
		 */
		static function in($value,$msg, $rules) {
			if(strstr($rules, ',')){
				if(!in_array($value, explode(',', $rules))){
					self::$msg[]=$msg;
					self::$flag=false;
				}	
			}else if(strstr($rules, '-')){
				list($min, $max)=explode('-', $rules);

				if(!($value>=$min && $value <=$max) ){
					self::$msg[]=$msg;
					self::$flag=false;
				}
			}else{
				if($rules!=$value){
					self::$msg[]=$msg;
					self::$flag=false;
				}
			}
		}
		/**
		 * 验证数据的值的长度是否在一定的范围内
		 * @param	string	$value	需要验证的值
		 * @param	string	$msg	验证失败的提示消息
		 * @param	string	$rules	一个范围，例如 3-20(3-20之间)、3,20(3-20之间)、3(必须是3个)、3,(3个以上)
		 */
		static function length($value,$msg, $rules) {
			$fg=strstr($rules, '-') ? '-' : ',';

			if(!strstr($rules, $fg)){
				if(utf8_strlen($value) != $rules){
					self::$msg[]=$msg;
					self::$flag=false;
				}
			}else{

				list($min, $max)=explode($fg, $rules);
				
				if(empty($max)){
					if(utf8_strlen($value) < $rules){
						self::$msg[]=$msg;
						self::$flag=false;
					}
				}else if(!(utf8_strlen($value)>=$min && utf8_strlen($value) <=$max) ){
					self::$msg[]=$msg;
					self::$flag=false;
				}
			}
		
		}
		/**
		 * 验证失败后的返回提示消息
		 */ 
		static function getMsg(){
			$msg=self::$msg;
			self::$msg='';
			self::$data=null;
			self::$action='';
			self::$flag=true;
			self::$db=null;
			self::$tablename='';
			self::$id='';
			return $msg;
		}

	}
?>