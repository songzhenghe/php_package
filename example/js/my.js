// JavaScript Document
function myconfirm(msg,addr){
	var result=confirm(msg);
	if(result==true){
		location.href=addr;
		return true;
	}else{
		return false;	
	}
}