<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-06 13:42:05
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-07 23:50:19
 */

/**
 * 1. give loans to all the users who have demanded for it
 * 2. check for loan, if moves > 2 and not repayed reduce resources
 * 3. attack on the users
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
$loan_query = "SELECT L.user_id, L.loan_amount, U.name FROM `user_loan_log` L INNER JOIN `users` U ON L.user_id = U.user_id WHERE L.taken_on='$current_move_number' AND L.returned=0";
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

/**
 * attack on the users
 */
$users_query = "SELECT `user_id`,`army` FROM `users` ORDER BY `user_id`";
$users_query_run = mysqli_query($connection, $users_query);

$user_army_details = array();
while($users_query_row = mysqli_fetch_assoc($users_query_run)){
	$temp_array = array(
		'id'					=> (int)$users_query_row['user_id'],
		'on_defence'			=> (int)$users_query_row['army'],
		'on_attack'				=> 0,
		'defence_percentage'	=> 1,
	);

	array_push($user_army_details, $temp_array);
}

$attack_army_query = "SELECT `from_id`,`army_enroute` FROM `attack_log` WHERE `move_number`='$current_move_number'";
$attack_army_query_run = mysqli_query($connection, $attack_army_query);

/**
 * "army_enroute" is the percentage of army 
 * which is sent for attacking the other user 
 */

while($attack_army_query_row = mysqli_fetch_assoc($attack_army_query_run)){
	$user_to_be_updated = (int)$attack_army_query_row['from_id'];
	$percentage_army_attacking = (int)$attack_army_query_row['army_enroute'];
	
	for($i=0;$i<count($user_army_details);$i++){
		if($user_army_details[$i]['id'] == $user_to_be_updated){
			$user_army_details[$i]['on_attack'] = ($percentage_army_attacking / 100) * $user_army_details[$i]['on_defence'];
			$user_army_details[$i]['on_defence'] = $user_army_details[$i]['on_defence'] - $user_army_details[$i]['on_attack'];
			$user_army_details[$i]['defence_percentage'] -= $percentage_army_attacking / 100;
			break;
		}
	}
}


for($i=0;$i<count($user_army_details);$i++){
	$user_attacked = (int)$user_army_details[$i]['id'];
	
	$attack_army_query = "SELECT `from_id` FROM `attack_log` WHERE `move_number`='$current_move_number' AND `to_id`='$user_attacked' ORDER BY `army_enroute` ASC";
	$attack_army_query_run = mysqli_query($connection, $attack_army_query);
	

	$total_army_attacking = 0;
	$maximum_attacker = -1;
	$maximum_attacker_army = 0;

	$attackers = array();
	while($attack_army_query_row = mysqli_fetch_assoc($attack_army_query_run)){
		for($j=0;$j<count($user_army_details);$j++){
			if($user_army_details[$j]['id'] == (int)$attack_army_query_row['from_id']){
				$total_army_attacking += $user_army_details[$j]['on_attack'];
				
				if($user_army_details[$j]['on_attack'] > $maximum_attacker_army){
					$maximum_attacker_army = $user_army_details[$j]['on_attack'];
					$maximum_attacker = $j;
				}

				array_push($attackers, $user_army_details[$j]['id']);
				break;
			}
		}
	}

	if($total_army_attacking >= $user_army_details[$i]['on_defence']){
		// defending user loses
	} else {
		// defending user wins		
	}
}

echo json_encode($user_army_details);
