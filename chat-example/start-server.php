<?php
	$output=null;
	$retval=null;
	exec("node index.js 2>&1", $output, $retval);
	exec('php node index.js', $output, $retval);
	echo "Returned with status $retval and output:\n";
	print_r($output);
?>