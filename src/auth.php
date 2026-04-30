<?php
include "session.php";
session_start();

try {
	if($_SERVER["REQUEST_METHOD"] !== "POST") throw new Exception("Unexpected HTTP request");
	if(empty($_POST["user"])) throw new Exception("Missing username in POST");
	if(empty($_POST["passw"])) throw new Exception("Missing password in POST");
	if(!password_verify($_POST["user"], $user_hash)) throw new Exception("Provided username is not admin's name");
	if(!password_verify($_POST["passw"], $pass_hash)) throw new Exception("Provided password is invalid");
	$_SESSION["admin"] = $_POST["user"];
	$_SESSION["stamp"] = time();
	session_regenerate_id(true);
	header("Location: settimes.php");
	exit(); // After redirect nothing to do here
} catch (Exception $e) {
	error_log($e->getMessage(), 0);
} catch (Throwable $t) {
	error_log($t->getMessage(), 0);
} finally {
	unset($_POST["passw"]);
}

// Empty and destroy session
$_SESSION = [];
session_destroy();
// Redirect user to new login attempt
header("Location: admin.php");

?>
