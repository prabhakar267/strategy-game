<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
<?php

	$current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$current_url = explode('/', $current_url);
	unset($current_url[count($current_url) - 1]);
	$current_url = implode('/', $current_url);
	
	echo "config_path_ajax = '" . $current_url . "/'\n";

?>
</script>
