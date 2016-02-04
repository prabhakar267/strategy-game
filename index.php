<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-04 18:25:15
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

require_once 'inc/layout/stylesheets.inc.php';
require_once 'inc/layout/scripts.inc.php';

if(isLoggedin()){
	echo '<a href="play.php">Play</a><br>';
	echo '<a href="logout.php">Logout</a><br>';
} else {
	echo '<a href="login.php">login</a><br>';
	echo '<a href="register.php">signup</a><br>';
}

echo '<a href="leaderboard.php">Leaderboard</a><br>';