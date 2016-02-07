<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 18:55:15
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-07 23:04:09
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


/**
 * function to make cURL calls
 * @param  string $url URL at which cURL call is to be made
 * @return string      output string
 */
function curl_URL_call($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}


/**
 * function to get the URL of the page
 * eg- "localhost/game/index.php" would return "http://localhost/game/"
 * @return string
 */
function return_base_URL(){
	$current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$current_url = explode('/', $current_url);
	unset($current_url[count($current_url) - 1]);
	$current_url = implode('/', $current_url);

	return $current_url . '/';
}


/**
 * function to get the resources of the user
 * @param  connection_object	$connection 
 * @param  integer 				$user_id    
 * @return array
 */
function get_user_resources($connection, $user_id){
	$query = "SELECT `army`,`money`,`land` FROM `users` WHERE `user_id`='$user_id' LIMIT 1";
	$query_row = mysqli_fetch_assoc(mysqli_query($connection, $query));

	return $query_row;
}
