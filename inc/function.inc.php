<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 18:55:15
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-01-31 21:04:18
 */


/**
 * function to check whether any user is loggedin or not
 * @return boolean 
 */
function isLoggedin(){
	return (bool)(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']));
}


/**
 * returns string which is safe for database and without any spaces
 * @param  string $str dirty string
 * @param  boolean $mode if mode is true, then remove white spaces else not
 * @return string      clean string
 */
function clean_string($str, $mode=true){
	$temp_str = htmlspecialchars(strip_tags(strtolower(trim($str))));
	if($mode)
		return str_replace(" ", "", $temp_str);
	else
		return $temp_str;
}


/**
 * function to put one way encrption over a string
 * @param  string $str 
 * @return string      encrypted string
 */
function encrypt_data($str){
	return md5($str);
}