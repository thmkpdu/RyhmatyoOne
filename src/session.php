<?php

$timeout = 60;

function session_is_valid() {
	global $timeout;

	try {
		if(empty($_SESSION["stamp"])) throw new Exception("Stamp not set in session");
		$_SESSION["timedout"] = time() - $_SESSION["stamp"] > $timeout;
		if($_SESSION["timedout"]) throw new Exception("Session timed out");
		if(empty($_SESSION["admin"])) throw new Exception("Admin not set in session");
	} catch(Exception $e) {
		error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
		return false;
	}
	return true;
}
?>
