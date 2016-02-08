<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-08 13:34:35
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-08 15:06:55
 */


require_once '../inc/connection.inc.php';
require_once '../inc/function.inc.php';


/**
 * this stores if the user has accepted to trade or not
 * 
 * 0 - accept
 * 1 - reject
 * 
 * @var integer
 */
$status = (bool)$_GET['flag'];
$trade_log_id = (int)$_GET['log'];

if($status){
	// accept to trade 

	$select_query = "SELECT * FROM trade_log WHERE `trade_log_id`='$trade_log_id' AND `status`=0 LIMIT 1";
	$select_query_row = mysqli_fetch_assoc(mysqli_query($connection, $select_query));

	/**
	 * to check if the request has already been served or not
	 */
	if(isset($select_query_row)){
		$army_change 	= (int)$select_query_row['army_offered'] - (int)$select_query_row['army_demanded'];
		$money_change 	= (int)$select_query_row['money_offered'] - (int)$select_query_row['money_demanded'];
		$land_change 	= (int)$select_query_row['land_offered'] - (int)$select_query_row['land_demanded'];

		$update_query1 = "UPDATE `users` SET `army`=`army`+'$army_change', `money`=`money`+'$money_change', `land`=`land`+'$land_change' WHERE `user_id`='" . (int)$select_query_row['from_id'] . "'";
		$update_query2 = "UPDATE `users` SET `army`=`army`-'$army_change', `money`=`money`-'$money_change', `land`=`land`-'$land_change' WHERE `user_id`='" . (int)$select_query_row['to_id'] . "'";
		$update_query3 = "UPDATE `trade_log` SET `status`='1' WHERE `trade_log_id`='$trade_log_id'";

		mysqli_query($connection, $update_query1);
		mysqli_query($connection, $update_query2);
		mysqli_query($connection, $update_query3);
	}
} else {
	// trade rejected
	$query = "UPDATE `trade_log` SET `status`='-1' WHERE `trade_log_id`='$trade_log_id'";
	mysqli_query($connection, $query);
}
