<?php
	$output=null;
	$retval=null;
	exec('php -q bin/server.php', $output, $retval);
	echo "Returned with status $retval and output:\n";
	print_r($output);
?>