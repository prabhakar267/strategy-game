<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 13:02:57
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-08 12:37:41
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

require_once 'inc/layout/stylesheets.inc.php';
require_once 'inc/layout/scripts.inc.php';

include 'inc/layout/header.inc.php';

if(isLoggedin()){
	echo '<a href="play.php"><button class="webkit-badge">Play</button></a>';
	echo '<a href="logout.php"><button class="webkit-badge">Logout</button></a>';
} else {
	echo '<a href="login.php"><button class="webkit-badge">Login</button></a>';
	echo '<a href="register.php"><button class="webkit-badge">Register</button></a> ';
}

echo '<a href="leaderboard.php"><button class="webkit-badge">Leaderboard</button></a>';
?>

