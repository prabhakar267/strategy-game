<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-01-31 21:01:42
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-01-31 21:07:10
 */

ob_start();
session_start();

/**
 * if there is any page from which logout has been called,
 * that would be saved in http_referer
 * @var string
 */
@$http_referer = $_SERVER['HTTP_REFERER'];