<?php
include "functions.php";
include "session.php";
session_start();

if(session_is_valid()) {
	$_SESSION["stamp"] = time();
} else {
	error_log($_SERVER['PHP_SELF'] . ": Session not valid", 0);
	session_clean_up();
	html_direct("Session timed out", "admin.php", 3, false);
	exit();
}

try {
	if($_SERVER["REQUEST_METHOD"] !== "POST") {
		throw new Exception("Unexpected http request");
	}
	if(empty($_POST["user"]) || empty($_POST["re_user"])) {
		throw new Exception("No new username has been entered");
	}
	if($_POST["user"] !== $_POST["re_user"]) {
		throw new Exception("New usernames don't match");
	}
	if(empty($_POST["old_user"])) {
		throw new Exception("No old username has been entered");
	}
	if(empty($_POST["passw"]) || empty($_POST["re_passw"])) {
		throw new Exception("No new password has been entered");
	}
	if($_POST["passw"] !== $_POST["re_passw"]) {
		throw new Exception("New passwords don't match");
	}
	if(empty($_POST["old_passw"])) {
		throw new Exception("No old password has been entered");
	}
	if($_POST["passw"] === $_POST["old_passw"]) {
		throw new Exception("New password is same as old password");
	}
	if(!password_verify($_POST["old_user"], $_SESSION["user_hash"])) {
		throw new Exception("Old username doesn't match");
	}
	if(!password_verify($_POST["old_passw"], $_SESSION["pass_hash"])) {
		throw new Exception("Old password doesn't match");
	}
} catch(Exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
	session_clean_up();
	html_direct($e->getMessage(), "admin.php", 3, false);
	exit();
}

try{
	$conf = require "../connection/connect.php";
	$driver = new mysqli_driver();
	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

	$conn = new mysqli($conf["host"], $conf["user"], $conf["pass"], $conf["db"]);
	$conn->set_charset("utf8mb4");

	$sql_cmd = $conn->prepare("UPDATE user SET name=?, pass=? WHERE id = 1");
	$u_hash = password_hash($_POST["user"], PASSWORD_DEFAULT);
	$p_hash = password_hash($_POST["passw"], PASSWORD_DEFAULT);
	$sql_cmd->bind_param("ss", $u_hash, $p_hash);
	$sql_cmd->execute();

	// Credentials succesfully changed

	session_clean_up();				//Clean up before relogin
	session_regenerate_id();		//For security
	header("Location: admin.php"); 	//Force to relogin
	exit();
} catch(mysqli_sql_exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
	session_clean_up();
	html_direct("Unable to save credentials. Try later again.", "admin.php", 3, false);
	exit();
} catch(Throwable $t) {
	error_log($_SERVER['PHP_SELF'] . ": " . $t->getMessage(), 0);
	session_clean_up();
	html_direct("Unable to save credentials. Try later again.", "admin.php", 3, false);
	exit();
}
?>
