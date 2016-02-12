<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-12 14:32:16
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
	<table class="table table-bordered table-hover" id="leaderboard_table">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Army</th>
				<th>Money</th>
				<th>Land</th>
				<th>points</th>
			</tr>
		</thead>
		<tbody>
			<tr>
<?php

	$query = "SELECT `name`,`army`,`money`,`land`, (3*`money`+2*`money`+`army`) AS `points` FROM `users` WHERE `disqualified`=0 ORDER BY `points` DESC";
	$query_run = mysqli_query($connection, $query);
	
	$i = 1;
	while($query_row = mysqli_fetch_assoc($query_run)){
		if($i == 1)
			echo '<tr class="success">';
		else
			echo '<tr>';

		echo '<th>' . $i++ . '</th>';
		echo '<td>' . clean_string($query_row['name'], false) . '</td>';
		echo '<td>' . (int)$query_row['army'] . '</td>';
		echo '<td>' . (int)$query_row['money'] . '</td>';
		echo '<td>' . (int)$query_row['land'] . '</td>';
		echo '<td>' . (int)$query_row['points'] . '</td>';
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
