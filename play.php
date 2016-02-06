<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-06 13:37:53
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

if(!isLoggedin()){
	header("Location: index.php");
}

if(isset($_POST['submit'])){
	/**
	 * $selected_move would tell about the type of move
	 * user has played in the current move
	 * @var integer
	 *
	 * Possible values are : 
	 * 0 - pass
	 * 1 - take loan from bank
	 * 2 - attack
	 * 3 - trade
	 */
	$selected_move = (int)$_POST['move']%4;
	$current_user_id = (int)$_SESSION['user_id'];
	$current_move_number = (int)$_POST['move_number'];

	switch($selected_move){
		case 0:
			// do nothing
			break;
		
		case 1:
			$loan_amount_wanted = (int)$_POST['loan_amount'];

			$loan_query = "INSERT INTO `user_loan_log` (`user_id`,`loan_amount`,`taken_on`) VALUES ('$current_user_id', '$loan_amount_wanted', '$current_move_number')";

			mysqli_query($connection, $loan_query);

			/**
			 * $new_amount this stores the sum of previous amount and loan amount
			 * @var integer
			 */
			$new_amount = $_SESSION['money'] + $loan_amount_wanted;
			$update_loan_query = "UPDATE `users` SET `money`='$new_amount' WHERE `user_id`='$current_user_id'";
			
			mysqli_query($connection, $update_loan_query);

			break;

		case 2:
			$team_on_attack = (int)$_POST['team_on_attack'];
			$army_on_defence = (int)$_POST['army_on_defence'];

			/**
			 * this stores the units of army going to attack other territory
			 * @var integer
			 */
			$army_for_attack = (100 - $army_on_defence) * $_SESSION['army'];
			
			$attack_log_query = "INSERT INTO `attack_log` (`move_number`,`from_id`,`to_id`,`army_enroute`) VALUES ('$current_move_number','$current_user_id','$team_on_attack','$army_for_attack')";

			mysqli_query($connection, $attack_log_query);
			
			break;

		default:
			$army_offered = (int)$_POST['army_offered'];
			$money_offered = (int)$_POST['money_offered'];
			$land_offered = (int)$_POST['land_offered'];

			$army_demanded = (int)$_POST['army_demanded'];
			$money_demanded = (int)$_POST['money_demanded'];
			$land_demanded = (int)$_POST['land_demanded'];

			$team_for_trade = (int)$_POST['team_for_trade'];

			$trade_query = "INSERT INTO `trade_log` (`move_number`,`from_id`,`to_id`,`army_offered`,`money_offered`,`land_offered`,`army_demanded`,`money_demanded`,`land_demanded`) VALUES ('$current_move_number','$current_user_id','$team_for_trade','$army_offered','$money_offered','$land_offered','$army_demanded','$money_demanded','$land_demanded')";

			mysqli_query($connection, $trade_query);

			break;
	}

	$_SESSION['move_number'] = $_SESSION['move_number'] + 1;

	// echo $selected_move;

	// echo json_encode($_POST);
	// die;
}

?>
<!doctype html>
<html>
<head>
<?php

require_once 'inc/layout/stylesheets.inc.php';

?>
</head>
<body>
<?php

	include 'inc/layout/header.inc.php';

?>
<div class="container">
	<form method="POST">
		<div class="form-group">
			<label>Move *</label>
			<select class="form-control" required name="move" id="move_select_input">
				<option value="0">Pass (Sure, kiddo?)</option>
				<option value="1">Take Loan</option>
				<option value="2">Attack (That's more like it)</option>
				<option value="3">Trade</option>
			</select>
		</div>
		<input class="hidden" name="move_number" id="move_number" />
		<div class="form-group" id="additional_info"></div>
		<button type="submit" class="btn btn-default" name="submit">Submit</button>
	</form>
</div>


<?php

require_once 'inc/layout/scripts.inc.php';

?>
<script src="js/play.js"></script>
</body>
</html>