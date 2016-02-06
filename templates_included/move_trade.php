<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-06 12:34:59
 */

require_once '../inc/connection.inc.php';
require_once '../inc/login_functions.inc.php';
require_once '../inc/function.inc.php';

if(!isLoggedin()){
	header("Location: index.php");
}

?>
<div class="row">
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<tr class="danger">
					<th>Resource offered</th>
					<th>Units of resources</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Army</td>
					<td><input type="number" class="form-control" placeholder="army you are giving away in trade" name="army_offered" required></td>
				</tr>
				<tr>
					<td>Money</td>
					<td><input type="number" class="form-control" placeholder="money you are giving away in trade" name="money_offered" required></td>
				</tr>
				<tr>
					<td>Land</td>
					<td><input type="number" class="form-control" placeholder="land you are giving away in trade" name="land_offered" required></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<tr class="success">
					<th>Resource demanded</th>
					<th>Units of resources</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Army</td>
					<td><input type="number" class="form-control" placeholder="army you are demanding in trade" name="army_demanded" required></td>
				</tr>
				<tr>
					<td>Money</td>
					<td><input type="number" class="form-control" placeholder="money you are demanding in trade" name="money_demanded" required></td>
				</tr>
				<tr>
					<td>Land</td>
					<td><input type="number" class="form-control" placeholder="land you are demanding in trade" name="land_demanded" required></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="clearfix"></div>

<div class="row">
	<div class="col-md-12">
		<select class="form-control" name="team_for_trade">
<?php
	
	$logged_in_user_id = $_SESSION['user_id'];
	$query = "SELECT `user_id`,`name` FROM `users` WHERE `user_id` != '$logged_in_user_id'";
	$query_run = mysqli_query($connection, $query);

	while($query_row = mysqli_fetch_assoc($query_run)){
		echo '<option value="' . (int)$query_row['user_id'] . '">' . clean_string($query_row['name'], false) . '</option>';
	}
	
?>
		</select>
	</div>
</div>