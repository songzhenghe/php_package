<?php
		if($string==''){
			$string=time();
		}
		if($type==1){
			$format='Y-m-d';
		}else{
			$format='Y-m-d H:i:s';
		}
		return date($format,$string);
	}
		preg_match_all("/./us", $string, $match);
		return count($match[0]);
	}