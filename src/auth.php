<?php

// HTML stub for error condition
$ERROR_HTML = '<!DOCTYPE html><html lang="en">';
$ERROR_HTML .= '<head><meta http-equiv="refresh" content="3; url=admin.php"></head>';
$ERROR_HTML .= '<body>';
$ERROR_HTML .= '<h1 style="text-align:center">Unable to authenticate. Try later again.</h1>';
$ERROR_HTML .= '</body>';
$ERROR_HTML .= '</html>';

session_start();
include "session.php";
if(session_is_valid()) {
	// Something is broken if execution enters here
	error_log($_SERVER['PHP_SELF'] . ": Session broken" ,0);
	session_clean_up();
	echo $ERROR_HTML;
	exit();
}

// Create user table
try {
	$conf = require "../connection/connect.php";
	$driver = new mysqli_driver();
	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

	$conn = new mysqli($conf["host"], $conf["user"], $conf["pass"], $conf["db"]);
	$conn->set_charset("utf8mb4");

	$sql = 'CREATE TABLE IF NOT EXISTS user ';
	$sql .= '(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ';
	$sql .= 'name VARCHAR(512) NOT NULL UNIQUE DEFAULT "placeholder", ';
	$sql .= 'pass VARCHAR(512) NOT NULL DEFAULT "placeholder")';
	$conn->query($sql);
} catch(mysqli_sql_exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
	echo $ERROR_HTML;
	exit();
}

// Insert default values for admin
try {
	// Admin has always id 1
	$sql = 'INSERT IGNORE INTO user (id, name, pass) VALUES(1, DEFAULT, DEFAULT)';
	$conn->query($sql);
	$conn->query($sql); //Second time just for testing
}catch(mysqli_sql_exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
	echo $ERROR_HTML;
	exit();
}

// Check if admin have changed values
try {
	$sql = 'SELECT COLUMN_DEFAULT AS "def" ';
	$sql .= 'FROM INFORMATION_SCHEMA.COLUMNS ';
	$sql .= 'WHERE TABLE_NAME = "user" ';
	$sql .= 'AND COLUMN_NAME = "pass"';
	$result = $conn->query($sql);
	$value = $result->fetch_assoc();
	$def_val = mb_substr($value["def"], 1, -1); //Strip leading and trailing '
	$sql = 'SELECT name, pass FROM user';
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$passwd_is_default = $row["pass"] === $def_val; //Evalutes true if default password in database.
	$_SESSION["user_hash"] = $row["name"];
	$_SESSION["pass_hash"] = $row["pass"];
} catch(mysqli_sql_exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
	echo $ERROR_HTML;
	exit();
}

if($passwd_is_default){
	// Force to change password

	// Sanity checks
	try {
		if($_SERVER["REQUEST_METHOD"] !== "POST") throw new Exception("Unexpected http request");
		if(empty($_POST["user"])) throw new Exception("Missing username in POST");
		if(empty($_POST["passw"])) throw new Exception("Missing password in POST");
	} catch(Exception $e) {
		error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
		session_clean_up();
		echo $ERROR_HTML;
		exit();
	}

	// Check if user submitted default credentials
	if(password_verify($_POST["user"], $_SESSION["user_hash"]) && password_verify($_POST["passw"], $_SESSION["pass_hash"])) {
		$_SESSION["admin"] = $_POST["user"];
		$_SESSION["stamp"] = time();
		session_regenerate_id(true);
		header("Location: change_creds.php");
		exit();
	} else {
		session_clean_up();
		session_regenerate_id(true);
		error_log( $_SERVER['PHP_SELF'] . ": Invalid DEFAULT admin credentials submitted", 0);
		echo $ERROR_HTML;
		exit();
	}
}

try {
	// Sanity check
	if($_SERVER["REQUEST_METHOD"] !== "POST") throw new Exception("Unexpected HTTP request");

	// Check if user submitted valid credentials
	if(!password_verify($_POST["user"], $_SESSION["user_hash"])) throw new Exception("Provided username is not admin's name");
	if(!password_verify($_POST["passw"], $_SESSION["pass_hash"])) throw new Exception("Provided password is invalid");

	$_SESSION["admin"] = $_POST["user"];
	$_SESSION["stamp"] = time();
	session_regenerate_id(true);
	header("Location: settimes.php");
	exit(); // After redirect nothing to do here
} catch (Exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage(), 0);
	session_clean_up();
	echo $ERROR_HTML;
	exit();
} catch (Throwable $t) {
	error_log($_SERVER['PHP_SELF'] . ": " . $t->getMessage(), 0);
	session_clean_up();
	echo $ERROR_HTML;
	exit();
} finally {
	unset($_POST["passw"]);
	exit();
}

// Something is wrong if execution enters here.
// Empty and destroy session
session_clean_up();
$_SESSION = [];
session_destroy();

error_log($_SERVER['PHP_SELF'] . ": Unknown error", 0);

// Redirect user to new login attempt
header("Location: admin.php");
?>

