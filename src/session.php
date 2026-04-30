<?php

$timeout = 60;

function session_is_valid() {
	global $timeout;

	if(empty($_SESSION["admin"])) {
		$_SESSION["admin"] = "";
		$_SESSION["stamp"] = "";
		return false;
	}

	if(empty($_SESSION["stamp"]) || time() - $_SESSION["stamp"] > $timeout) {
		$_SESSION["admin"] = "";
		$_SESSION["stamp"] = "";
		return false;
	}

	return true;
}
?>
