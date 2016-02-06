<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-06 13:29:39
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

if(isLoggedin()){
	header("Location: index.php");
}

if(isset($_POST['submit'])){
	$entered_email 	= clean_string($_POST['email']);
	$entered_pass	= encrypt_data(clean_string($_POST['pass']));
	
	$query = "SELECT `user_id`,`name`,`army`,`money`,`land` FROM `users` WHERE `email_id`='$entered_email' AND `password`='$entered_pass' LIMIT 1";

	$query_row = mysqli_fetch_assoc(mysqli_query($connection, $query));
	
	if(isset($query_row['user_id'])){
		$_SESSION['user_id'] 		= (int)$query_row['user_id'];
		$_SESSION['name'] 			= clean_string($query_row['name']);
		$_SESSION['army']		 	= (int)$query_row['army'];
		$_SESSION['money'] 			= (int)$query_row['money'];
		$_SESSION['land'] 			= (int)$query_row['land'];

		header("Location: index.php");
	} else {
		$error = true;
		$message = "Something's wrong!<br><strong>Incorrect Email ID - Password combination</strong>";
	}
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
			<label>Email address*</label>
			<input type="email" class="form-control" placeholder="Email" name="email" required autofocus>
		</div>
		<div class="form-group">
			<label>Password</label>
			<input type="password" class="form-control" placeholder="Password" name="pass" required>
		</div>
		<button type="submit" class="btn btn-default" name="submit">Submit</button>
	</form>
</div>


<?php

require_once 'inc/layout/scripts.inc.php';

?>
</body>
</html>