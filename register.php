<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-14 23:11:14
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

if(isLoggedin()){
	header("Location: index.php");
}

if(isset($_POST['submit'])){
	$entered_name		= clean_string($_POST['name'], false);
	$entered_email 		= clean_string($_POST['email']);
	$entered_pass		= encrypt_data(clean_string($_POST['pass']));
	$entered_conf_pass	= encrypt_data(clean_string($_POST['confpass']));
	$entered_college	= clean_string($_POST['college'], false);
	
	if($entered_pass != $entered_conf_pass){
		$error = true;
		$message = "You had one job!<br><strong>You did not enter same password twice</strong>";
	} else {
		$query = "SELECT `name` FROM `users` WHERE `email_id`='$entered_email' LIMIT 1";

		$query_row = mysqli_fetch_assoc(mysqli_query($connection, $query));
		
		if(isset($query_row['user_id'])){
			$error = true;
			$message = "I remember you " . $query_row['name'] . "!<br><strong>This Email ID has already been used</strong>";
		} else {
			$query = "INSERT INTO `users` (`name`,`email_id`,`college`,`password`) VALUES ('$entered_name','$entered_email','$entered_college','$entered_pass')";

			if(mysqli_query($connection, $query)){
				header("Location: login.php");
			} else {
				$error = true;
				$message = "Some Technical Glitch!<br><strong>Try again!</strong>";
			}
		}
	}
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

	include 'inc/layout/header.inc.php';

?>
<div class="container">
<?php

if(isset($error)){
	echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>' . $message . '</div>';
}

?>
	<form method="POST">
		<div class="form-group">
			<label>Name *</label>
			<input type="text" class="form-control" placeholder="Name" name="name" required autofocus>
		</div>
		<div class="form-group">
			<label>College *</label>
			<input type="text" class="form-control" placeholder="College" name="college" required>
		</div>
		<div class="form-group">
			<label>Email address *</label>
			<input type="email" class="form-control" placeholder="Email" name="email" required>
		</div>
		<div class="form-group">
			<label>Password *</label>
			<input type="password" class="form-control" placeholder="Password" name="pass" required>
		</div>
		<div class="form-group">
			<label>Confirm Password *</label>
			<input type="password" class="form-control" placeholder="Password" name="confpass" required autocomplete="off">
		</div>
		<button type="submit" class="btn btn-default" name="submit">Submit</button>
	</form>
</div>


<?php

require_once 'inc/layout/scripts.inc.php';

?>
</body>
</html>