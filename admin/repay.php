<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-08 12:40:23
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-08 13:02:24
 */


require_once '../inc/connection.inc.php';
require_once '../inc/function.inc.php';

$url = return_base_URL() . "current_level.php";
$current_move_number = json_decode(curl_URL_call($url), true);
$current_move_number = (int)$current_move_number['level'];

if(isset($_GET['user_id'])){
	$user_id = (int)$_GET['user_id'];
	$query = "SELECT `loan_amount` FROM `user_loan_log` WHERE `user_id`='$user_id' AND `returned`=0";

	$query_row = mysqli_fetch_assoc(mysqli_query($connection, $query));
	$loan_amount = $query_row['loan_amount'];

	if(isset($loan_amount)){
		$update_query = "UPDATE `user_loan_log` L, `users` U SET L.returned = 1, U.money = U.money - '$loan_amount' WHERE L.user_id = U.user_id AND L.returned = 0 AND U.user_id = '$user_id'";
		if(mysqli_query($connection, $update_query)){
			$success = true;
		} else {
			$success = false;
		}
	} else {
		// this means there are no outstanding loans
		// for this user_id
		$success = false;
	}
}

$final_response = array(
	'success'	=> (bool)$success,
);

echo json_encode($final_response);
