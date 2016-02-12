<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:15:13
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-12 14:54:52
 */

	$connect_error = 'Could not connect';
	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_pass = '696163';
	$mysql_data = 'strategy_game';

	if(!$connection = mysqli_connect($mysql_host , $mysql_user , $mysql_pass, $mysql_data))
		die(mysqli_error($connection));