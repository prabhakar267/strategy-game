<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-06 13:42:05
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-06 14:33:03
 */

/**
 * 1. give loans to all the users who have demanded for it
 * 2. check for loan, if moves > 2 and not repayed reduce resources
 * 3. attack on the people
 * 4. update move number
 */


require_once '../inc/connection.inc.php';
require_once '../inc/login_functions.inc.php';
require_once '../inc/function.inc.php';
require_once '../inc/globals.inc.php';


$url = return_base_URL() . "current_level.php";
$current_move_number = json_decode(curl_URL_call($url), true);
$current_move_number = (int)$current_move_number['level'];

$final_response = array();


/**
 * give loans to all the users who have demanded for it
 */
$loan_query = "SELECT L.user_id, L.loan_amount, U.name FROM `user_loan_log` L INNER JOIN `users` U ON L.user_id = U.user_id WHERE L.taken_on='$current_move_number'";
$loan_query_run = mysqli_query($connection, $loan_query);

while($loan_query_row = mysqli_fetch_assoc($loan_query_run)){
	$amount_to_be_added = (int)$loan_query_row['loan_amount'];
	$user_taking_loan 	= $loan_query_row['user_id'];
	$name				= $loan_query_row['name'];
	
	$query_add_loan = "UPDATE `users` SET `money`=`money` + '$amount_to_be_added' WHERE `user_id`='$user_taking_loan'";
	if(mysqli_query($connection, $query_add_loan)){
		$message = 'user ' . $name . ' given loan of ' . $amount_to_be_added . ' units';
		array_push($final_response, $message);
	}
}


/**
 * check for loan, if moves > 2 and not repayed reduce resources
 */
$loan_check_move_number = $current_move_number - 2;
$loan_recovery_percentage = LOAN_RECOVERY_PERCENTAGE;

$loan_query = "SELECT L.user_id, U.name FROM `user_loan_log` L INNER JOIN `users` U ON L.user_id = U.user_id WHERE L.taken_on <= '$loan_check_move_number' AND L.returned=0";
$loan_query_run = mysqli_query($connection, $loan_query);

while($loan_query_row = mysqli_fetch_assoc($loan_query_run)){
	$user_for_recovery 	= (int)$loan_query_row['user_id'];
	$name_of_user		= $loan_query_row['name'];
	
	$query_recover_loan = "UPDATE `users` U,`user_loan_log` L SET U.army = '$loan_recovery_percentage'*U.army, U.money = '$loan_recovery_percentage'*U.money, U.land = '$loan_recovery_percentage'*U.land, L.returned=1 WHERE U.user_id = L.user_id AND U.user_id='$user_for_recovery' AND L.returned = 0";
	if(mysqli_query($connection, $query_recover_loan)){
		$message = 'user ' . $name_of_user . ' didn\'t pay its loan hence IRON BANK acted';
		array_push($final_response, $message);
	}
}

echo json_encode($final_response);
