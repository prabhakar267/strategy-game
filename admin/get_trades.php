<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-08 13:02:42
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-08 13:25:36
 */


require_once '../inc/connection.inc.php';
require_once '../inc/login_functions.inc.php';
require_once '../inc/function.inc.php';
require_once '../inc/globals.inc.php';


$url = return_base_URL() . "current_level.php";
$current_move_number = json_decode(curl_URL_call($url), true);
$current_move_number = (int)$current_move_number['level'];
$last_move = $current_move_number - 1;

$final_response = array();

if(isset($_GET['user_id'])){
	/**
	 * this represents whether output represents the outstanding trades 
	 * or those trades which were initiated by this user
	 * 
	 * 0 - outstanding trades
	 * 1 - trades which were initiated by this user
	 * @var integer
	 */
	$mode = @((int)$_GET['mode'])%2;
	$user_id = (int)$_GET['user_id'];

	if((bool)$mode){
		$query = "SELECT T.trade_log_id, U.name, T.army_offered, T.money_offered, T.land_offered, T.army_demanded, T.money_demanded, T.land_demanded, T.status FROM trade_log T INNER JOIN users U ON T.to_id = U.user_id WHERE T.status=0 AND T.from_id='$user_id'";
	} else {
		$query = "SELECT T.trade_log_id, U.name, T.army_offered, T.money_offered, T.land_offered, T.army_demanded, T.money_demanded, T.land_demanded FROM trade_log T INNER JOIN users U ON T.from_id = U.user_id WHERE T.status=0 AND T.move_number='$last_move' AND T.to_id='$user_id'";
	}
	$query_run = mysqli_query($connection, $query);

	while($query_row = mysqli_fetch_assoc($query_run)){
		array_push($final_response, $query_row);
	}
}

echo json_encode($final_response);
