<?php
/**
 * @Author: Prabhakar Gupta
 * @Date:   2016-02-06 13:08:50
 * @Last Modified by:   Prabhakar Gupta
 * @Last Modified time: 2016-02-06 13:28:11
 */


$file_handle = fopen("current_level", "r");
$current_level = fread($file_handle, filesize("current_level"));

$response = array(
	'level'	=> (int)$current_level,
);

echo json_encode($response);

fclose($file_handle);
