<?php 
echo "<pre>";
print_r($_POST);
echo "</pre>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title></title>
<script>
function formcheck_username(obj){
	val=obj.user_name.value;
	val=val.replace(/^\s+|\s+$/g,"");
	if(val=="" || val==null || val.length==0){
		obj.user_name.disabled=true;
	}
	return true;
}
function formcheck_password(obj){
	val=obj.user_password.value;
	val=val.replace(/^\s+|\s+$/g,"");
	if(val=="" || val==null || val.length==0){
		obj.user_password.disabled=true;
		obj.user_password2.disabled=true;
	}
	return true;
}
</script>
</head>
<body>
<form action="" method="post" name="form1" onsubmit="return (formcheck_username(this) && formcheck_password(this));">
用户名：<input name="user_name" type="text" /><br />
旧密码：<input name="user_password_old" type="password" /><br />
新密码：<input name="user_password" type="password" /><br />
再次新密码：<input name="user_password2" type="password" /><br />
<input name="Submit" type="submit" value="提交" />
</form>
</body>
</html>
