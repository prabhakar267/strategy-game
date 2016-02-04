<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 21:07:32
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-01-31 21:08:02
 */

require_once 'inc/login_functions.inc.php';

session_destroy();

if($http_referer)
	header('Location: '.$http_referer);
else
	header('Location: login.php');