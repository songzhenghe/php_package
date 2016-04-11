<?php
/*
CREATE TABLE IF NOT EXISTS `yi_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varchar(20) NOT NULL,
  `user_password` char(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tag` tinyint(3) unsigned NOT NULL,
  `reg_time` int(10) unsigned NOT NULL,
  `login_time` int(10) unsigned NOT NULL,
  `login_ip` char(15) NOT NULL,
  `times` int(10) unsigned NOT NULL,
  `allow_del` tinyint(3) unsigned NOT NULL default '0',
  `myhash` char(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
*/
abstract class user_login_exp extends service{
	//传入的数据
	protected $in_data=null;
	//用户数据
	protected $user_data=null;
	//初始化session MY code
	protected function init_session(){
		$_SESSION['MY']=array();
	}
	protected function fill_session(){
		$_SESSION['MY']['ISLOGIN']=1;
		$_SESSION['MY']['ID']=$this->user_data['id'];
		$_SESSION['MY']['USER_NAME']=$this->user_data['user_name'];
		$_SESSION['MY']['LOGIN_IP']=$this->user_data['login_ip'];
		$_SESSION['MY']['LOGIN_TIME']=$this->user_data['login_time'];
		$_SESSION['MY']['TIMES']=$this->user_data['times'];
	}
	//结束session MY code
	protected function finish_session(){
		$_SESSION=array();
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(),'',time()-3600,'/');
		}
		session_destroy();
		return true;
	}
	//判断所有表单项是否填写
	protected function check_blank(){
		if($this->in_data['user_name']=='' or $this->in_data['user_password']=='' or $this->in_data['code']==''){
			return false;
		}
		return true;
	}
	//判断验证码是否正确
	protected function check_authcode(){
		return strtoupper($this->in_data['code'])==$_SESSION['code'];
	}
	//判断用户是否存在
	protected function check_user_exist(){
		if(empty($this->user_data) or $this->user_data['id']==''){
			return false;
		}
		return true;
	}
	//判断密码是否正确
	protected function check_user_password(){
		return md5password($this->in_data['user_password'])==$this->user_data['user_password'];
	}
	//读取一条用户信息
	protected function get_user(){
		$user_name=$this->in_data['user_name'];
		$query="select `id`,`user_name`,`user_password`,`login_time`,`login_ip`,`times` from `{$this->prefix}user` where `user_name`='{$user_name}' and `tag`='1' limit 1";
		$info=$this->sql_func->select($query);
		$this->user_data=is_array($info)?$info:array();
		return $info;
	}
	//更新 login_time,login_ip,times
	protected function update_user(){
		$id=$this->user_data['id'];
		$login_time=time();
		$login_ip=$_SERVER['REMOTE_ADDR'];
		$query="update `{$this->prefix}user` set `login_time`='{$login_time}',`login_ip`='{$login_ip}',`times`=`times`+1 where `id`='{$id}' limit 1";
		$this->sql_func->query($query);
	}
	//示例用户登陆程序
	protected function example_user_login($in_data){
		$this->in_data=$in_data;
		if($this->check_blank()==false){
			return -1;//信息未填写完全
		}
		if(!$this->check_authcode()){
			return -2;//验证码不正确
		}
		$this->get_user();
		if(!$this->check_user_exist()){
			return -3;//用户不存在
		}
		if(!$this->check_user_password()){
			return -4;//密码不正确
		}
		$this->update_user();
		$this->init_session();
		$this->fill_session();
		return 1;//登陆成功
	}
	abstract public function user_login($in_data);
	//示例用户登出程序
	protected function example_user_logout(){
		$this->finish_session();
		return true;
	}
	abstract public function user_logout();
	//示例获取用户session信息
	protected function example_user_session($name=''){
		$name=strtoupper($name);
		return ($name=='')?$_SESSION['MY']:$_SESSION['MY'][$name];
	}
	abstract public function user_session($name='');
}