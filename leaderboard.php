<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-12 12:39:00
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

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

	include 'inc/layout/navbar.inc.html';
	include 'inc/layout/header.inc.php';

?>
<div class="container">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Army</th>
				<th>Money</th>
				<th>Land</th>
			</tr>
		</thead>
		<tbody>
			<tr>
<?php

	$query = "SELECT `name`,`army`,`money`,`land` FROM `users` WHERE `disqualified`=0 ORDER BY `user_id`";
	$query_run = mysqli_query($connection, $query);
	
	$i = 1;
	while($query_row = mysqli_fetch_assoc($query_run)){
		echo '<tr>';
		echo '<td>' . $i++ . '</td>';
		echo '<td>' . clean_string($query_row['name'], false) . '</td>';
		echo '<td>' . (int)$query_row['army'] . '</td>';
		echo '<td>' . (int)$query_row['money'] . '</td>';
		echo '<td>' . (int)$query_row['land'] . '</td>';
		echo '</tr>';
	}

?>
			</tr>
		</tbody>
	</table>
</div>
<?php

require_once 'inc/layout/scripts.inc.php';

?>
</body>
</html>
