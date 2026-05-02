<?php

$timeout = 60;
$user_hash = "";
$pass_hash = "";

function session_is_valid() {
	global $timeout;

	try {
		if(empty($_SESSION["stamp"])) throw new Exception("Stamp not set in session");
		if(time() - $_SESSION["stamp"] > $timeout) throw new Exception("Session timed out");
		if(empty($_SESSION["admin"])) throw new Exception("Admin not set in session");
	} catch(Exception $e) {
		unset($_SESSION["admin"]);
		$_SESSION["stamp"] = 0;
		setcookie(session_name(), '', 1);
		error_log("session.php: " . $e->getMessage(), 0);
		return false;
	}
	return true;
}

function session_clean_up() {
	unset($_POST["user"]);
	unset($_POST["re_user"]);
	unset($_POST["old_user"]);
	unset($_POST["passw"]);
	unset($_POST["re_passw"]);
	unset($_POST["old_passw"]);
	unset($_SESSION["user_hash"]);
	unset($_SESSION["pass_hash"]);
	unset($_SESSION["admin"]);
	$_SESSION["stamp"] = 0;
	setcookie(session_name(), '', 1);
}
?>
