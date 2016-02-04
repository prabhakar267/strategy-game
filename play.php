<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-05 03:57:24
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
	
	switch($selected_move){
		case 0:
			// do nothing
			break;
		
		case 1:
			$loan_amount_wanted = (int)$_POST['loan_amount'];
			break;

		case 2:
			$team_on_attack = (int)$_POST['team_on_attack'];
			$army_on_defence = (int)$_POST['army_on_defence'];
			
			break;

		default:
			$army_amount = (int)$_POST['army_amount'];
			$money_amount = (int)$_POST['money_amount'];
			$land_amount = (int)$_POST['land_amount'];

			break;
	}

	echo $selected_move;

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