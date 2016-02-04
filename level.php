<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 22:04:55
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-01-31 23:12:12
 */

require_once 'inc/connection.inc.php';
require_once 'inc/login_functions.inc.php';
require_once 'inc/function.inc.php';

if(!isLoggedin()){
	header("Location: login.php");
}

