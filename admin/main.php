<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-06 13:42:05
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-09 15:58:47
 */

/**
 * 1. give loans to all the users who have demanded for it
 * 2. check for loan, if moves > 2 and not repayed reduce resources
 * 3. convert money to army
 * 4. attack on the users
 * 5. update move number
 */


require_once '../inc/connection.inc.php';
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
 * convert money to army
 */
$army_trade_query = "SELECT L.user_id, L.army_wanted, U.army, U.money, U.name FROM `army_purchase_log` L INNER JOIN `users` U ON L.user_id = U.user_id WHERE L.move_number='$current_move_number'";
$army_trade_query_run = mysqli_query($connection, $army_trade_query);

while($army_trade_query_row = mysqli_fetch_assoc($army_trade_query_run)){
	$user_id 		= (int)$army_trade_query_row['user_id'];
	$army_wanted	= (int)$army_trade_query_row['army_wanted'];
	$current_army	= (int)$army_trade_query_row['army'];
	$current_money	= (int)$army_trade_query_row['money'];
	$user_name		= $army_trade_query_row['name'];
	
	if($army_wanted*ARMY_MONEY_CONVERSION <= $current_money){
		$money_deducted = $army_wanted*ARMY_MONEY_CONVERSION;
		$update_query = "UPDATE `users` SET `army`='$current_army'+'$army_wanted', `money`=`money`-'$money_deducted' WHERE `user_id`='$user_id'";
		if(mysqli_query($connection, $query_recover_loan)){
			$message = 'user ' . $user_name . ' bought '. $army_wanted . ' from ARMY PIT';
			array_push($final_response, $message);
		}
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

/**
 * this array stores the changes to be done in each user's data
 * @var array
 */
$user_changes = array();
for($i=0;$i<count($user_army_details);$i++){
	$temp_array = array(
		'id'	=> (int)$user_army_details[$i]['id'],
		'army'	=> 0,
		'money'	=> 0,
		'land'	=> 0,
	);

	array_push($user_changes, $temp_array);
}

for($i=0;$i<count($user_army_details);$i++){
	$user_attacked = (int)$user_army_details[$i]['id'];
	
	$attack_army_query = "SELECT `from_id` FROM `attack_log` WHERE `move_number`='$current_move_number' AND `to_id`='$user_attacked' ORDER BY `army_enroute` ASC";
	$attack_army_query_run = mysqli_query($connection, $attack_army_query);
	

	$total_army_attacking = 0;
	$maximum_attacker = -1;
	$maximum_attacker_army = 0;

	/**
	 * array of all the users attacking current team
	 * @var array
	 */
	$attackers = array();
	while($attack_army_query_row = mysqli_fetch_assoc($attack_army_query_run)){
		for($j=0;$j<count($user_army_details);$j++){
			if($user_army_details[$j]['id'] == (int)$attack_army_query_row['from_id']){
				$total_army_attacking += (int)$user_army_details[$j]['on_attack'];
				
				if($user_army_details[$j]['on_attack'] > $maximum_attacker_army){
					$maximum_attacker_army = $user_army_details[$j]['on_attack'];
					$maximum_attacker = $user_army_details[$j]['id'];
				}

				array_push($attackers, $user_army_details[$j]['id']);
				break;
			}
		}
	}

	// echo $total_army_attacking;

	if($total_army_attacking >= $user_army_details[$i]['on_defence']){
		// defending user loses
		// user with maximum attacking army gets the resources of defender
		
		$defenders_defence_percentage = (float)$user_army_details[$i]['defence_percentage'];
		$defenders_resource_array = get_user_resources($connection, $user_attacked);

		for($j=0;$j<count($user_changes);$j++){
			if($user_army_details[$j]['id'] == $user_attacked){
				$user_changes[$j]['army'] -= $user_army_details[$i]['on_defence'];
				$user_changes[$j]['money'] -= (int)$defenders_resource_array['money'] * $defenders_defence_percentage;
				$user_changes[$j]['land'] -= (int)$defenders_resource_array['land'] * $defenders_defence_percentage;
			}
			
			if($user_army_details[$j]['id'] == $maximum_attacker){
				$user_changes[$j]['money'] += (int)$defenders_resource_array['money'] * $defenders_defence_percentage;
				$user_changes[$j]['land'] += (int)$defenders_resource_array['land'] * $defenders_defence_percentage;
			}
		}

		// kill half the attack army for all the attackers
		foreach($attackers as $attacker){
			for($j=0;$j<count($user_changes);$j++){
				if($user_changes[$j]['id'] == $attacker){
					for($k=0;$k<count($user_army_details);$k++){
						if($user_army_details[$k]['id'] == $attacker){
							$user_changes[$j]['army'] -= (int)$user_army_details[$k]['on_attack'] * ATTACK_LOSS;
							break;
						}	
					}
					break;
				}
			}	
		}
	} else {
		// defending user wins		
		// defender gets all the resources from all the attackers
		
		$money_for_defender = 0;
		$land_for_defender = 0;

		foreach($attackers as $attacker){
			for($j=0;$j<count($user_army_details);$j++){
				if($user_army_details[$j]['id'] == $attacker){
					$attacker_attack_percent = 1 - $user_army_details[$j]['defence_percentage'];
					$attackers_resource_array = get_user_resources($connection, $attacker);

					$user_changes[$j]['army'] -= (int)$attackers_resource_array['army'] * $attacker_attack_percent;
					$user_changes[$j]['money'] -= (int)$attackers_resource_array['money'] * $attacker_attack_percent;
					$user_changes[$j]['land'] -= (int)$attackers_resource_array['land'] * $attacker_attack_percent;

					$money_for_defender += (int)$attackers_resource_array['money'] * $attacker_attack_percent;
					$land_for_defender += (int)$attackers_resource_array['land'] * $attacker_attack_percent;
					break;
				}
			}
		}

		for($j=0;$j<count($user_changes);$j++){
			if($user_changes[$j]['id'] == $user_attacked){
				$user_changes[$j]['army'] -= $user_army_details[$i]['on_defence'] * ATTACK_LOSS;

				$user_changes[$j]['money'] += $money_for_defender;
				$user_changes[$j]['land'] += $land_for_defender;

				break;
			}
		}
	}
}

// commit changes to the database
foreach($user_changes as $change){
	$user_id = (int)$change['id'];
	$user_army_change = (int)$change['army'];
	$user_money_change = (int)$change['money'];
	$user_land_change = (int)$change['land'];

	$query = "UPDATE `users` SET `army`=`army`+'$user_army_change', `money`=`money`+'$user_money_change', `land`=`land`+'$user_land_change' WHERE `user_id`='$user_id'";
	mysqli_query($connection, $query);
}


/**
 * Update move number
 */
$file_name = "current_level";

$file_handler = fopen($file_name, "w");

$new_move_number = $current_move_number + 1;
fwrite($file_handler, $new_move_number);

fclose($file_handler);


echo json_encode($final_response);
