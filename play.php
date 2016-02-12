<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-12 15:38:08
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

if(!isLoggedin()){
	header("Location: index.php");
}

$current_user_id = (int)$_SESSION['user_id'];
$query = "SELECT `move_number`,`army`,`money`,`land` FROM `users` WHERE `user_id`='$current_user_id'";
$query_row = mysqli_fetch_assoc(mysqli_query($connection, $query));

$user_move_number = (int)$query_row['move_number'];
$_SESSION['army'] = (int)$query_row['army'];
$_SESSION['money'] = (int)$query_row['money'];
$_SESSION['land'] = (int)$query_row['land'];

$url = return_base_URL() . "admin/current_level.php";
$admin_current_move_number = json_decode(curl_URL_call($url), true);
$admin_current_move_number = (int)$admin_current_move_number['level'];

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
	$selected_move = (int)$_POST['move']%5;
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
			$army_for_attack = (100 - $army_on_defence);
			
			$attack_log_query = "INSERT INTO `attack_log` (`move_number`,`from_id`,`to_id`,`army_enroute`) VALUES ('$current_move_number','$current_user_id','$team_on_attack','$army_for_attack')";

			mysqli_query($connection, $attack_log_query);
			
			break;

		case 3:
			$army_offered = (int)$_POST['army_offered'];
			$money_offered = (int)$_POST['money_offered'];
			$land_offered = (int)$_POST['land_offered'];

			$army_demanded = (int)$_POST['army_demanded'];
			$money_demanded = (int)$_POST['money_demanded'];
			$land_demanded = (int)$_POST['land_demanded'];

			$team_for_trade = (int)$_POST['team_for_trade'];

			$trade_query = "INSERT INTO `trade_log` (`move_number`,`from_id`,`to_id`,`army_offered`,`money_offered`,`land_offered`,`army_demanded`,`money_demanded`,`land_demanded`) VALUES ('$current_move_number','$current_user_id','$team_for_trade','$army_demanded','$money_demanded','$land_demanded','$army_offered','$money_offered','$land_offered')";

			mysqli_query($connection, $trade_query);

			break;

		case 4:
			$army_wanted = (int)$_POST['army_wanted'];
			$army_wanted_query = "INSERT INTO `army_purchase_log` (`user_id`,`army_wanted`,`move_number`) VALUES ('$current_user_id','$army_wanted','$current_move_number')";

			mysqli_query($connection, $army_wanted_query);

			break;
	}

	$incease_move_number_query = "UPDATE `users` SET `move_number`='$admin_current_move_number' WHERE `user_id`='$current_user_id'";
	mysqli_query($connection, $incease_move_number_query);

	header("Location: play.php");
}

?>
<!doctype html>
<html>
<head>
<?php

include 'inc/layout/meta.inc.php';
require_once 'inc/layout/stylesheets.inc.php';

?>
</head>
<body>
<?php

	include 'inc/layout/navbar.inc.html';
	include 'inc/layout/header.inc.php';

?>

<div class="col-md-12">
	<div class="col-md-3">
		<h2 class="requests-header"><a class="hint--bottom-right hint--bounce" data-hint="Trade requests which are raised by the other families">Outstanding Requests</a></h2>
		<div id="requests_for_me">Loading...</div>
	</div>
	<div class="col-md-6 main-move-div">
		<div class="col-md-12">
			<div class="col-md-4 zero-padding">
				<center><label>Army</label></center>
				<input type="number" class="form-control" disabled value="<?php echo $_SESSION['army']; ?>">
			</div>
			<div class="col-md-4 zero-padding">
				<center><label>Money</label></center>
				<input type="number" class="form-control" disabled value="<?php echo $_SESSION['money']; ?>">
			</div>
			<div class="col-md-4 zero-padding">
				<center><label>Land</label></center>
				<input type="number" class="form-control" disabled value="<?php echo $_SESSION['land']; ?>">
			</div>
		</div>
		<div class="clearfix"></div>
		<hr>
		<form method="POST">
			<div class="form-group">
				<label>Move <?php echo $admin_current_move_number; ?> *</label>
				<select class="form-control" required name="move" id="move_select_input">
					<option value="0">Pass (Sure, kiddo?)</option>
					<option value="1">Take Loan</option>
					<option value="2">Attack (That's more like it)</option>
					<option value="3">Trade</option>
					<option value="4">Buy Army</option>
				</select>
			</div>
			<input class="hidden" name="move_number" id="move_number" />
			<input class="hidden" id="user_id" value="<?php echo (int)$_SESSION['user_id']; ?>"/>
			<div class="form-group" id="additional_info"></div>
<?php

	if($user_move_number < $admin_current_move_number){
		echo '<button type="submit" class="btn btn-success btn-lg" name="submit">Submit</button>';
	} else {
		echo 'your <strong>move ' . $admin_current_move_number . '</strong> has been recorded!<br>';
		echo '<div class="text-right"><a href="play.php"><button type="button" class="btn btn-success">refresh</button></a></div>';
	}

?>			
		</form>
	</div>
	<div class="col-md-3">
		<h2 class="requests-header"><a class="hint--bottom-left hint--bounce" data-hint="Trade requests you raised so far">Your trade history</a></h2>
		<div class="col-md-12" id="requests_from_me">Loading...</div>
	</div>
</div>


<?php

require_once 'inc/layout/scripts.inc.php';

?>
<script src="js/play.js"></script>
</body>
</html>