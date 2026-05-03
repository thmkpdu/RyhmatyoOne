<?php
include "session.php";
include "functions.php";
session_start();

if(!session_is_valid()) {
	session_clean_up();
	html_direct("Sorry, session timed out", "admin.php", 3, false);
	exit();
}

$_SESSION["stamp"] = time();

try {
	$conf = require "../connection/connect.php";

	$driver = new mysqli_driver();
	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

	$conn = new mysqli($conf['host'], $conf["user"], $conf["pass"], $conf["db"]);
	$conn->set_charset('utf8mb4');

	$sql = 'CREATE TABLE IF NOT EXISTS times ';
	$sql .= '(open BOOLEAN NOT NULL, ';
	$sql .= 'opentime CHAR(5) NOT NULL, ';
	$sql .= 'closetime CHAR(5) NOT NULL)';
	$conn->query($sql);
} catch(mysqli_sql_exception $e) {
	error_log($e->getMessage());
	html_direct("Sorry, session timed out", "admin.php", 3, false);
	exit();
}

// Pick values for SQL statements from POSTED values
try {
    if($_SERVER["REQUEST_METHOD"] !== "POST") throw new Exception("Unexpected HTTP request");

    $closed = [false, "00:00", "00:00"];

	if(!empty($_POST["Mon"]) && !empty($_POST["MonOpen"]) && !empty($_POST["MonClose"])) {
		$data[] = [true, $_POST["MonOpen"], $_POST["MonClose"]];
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Tue"]) && !empty($_POST["TueOpen"]) && !empty($_POST["TueClose"])) {
		$data[] = [true, $_POST["TueOpen"], $_POST["TueClose"]];
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Wed"]) && !empty($_POST["WedOpen"]) && !empty($_POST["WedClose"])) {
		$data[] = [true, $_POST["WedOpen"], $_POST["WedClose"]];
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Thu"]) && !empty($_POST["ThuOpen"]) && !empty($_POST["ThuClose"])) {
		$data[] = [true, $_POST["ThuOpen"], $_POST["ThuClose"]];
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Fri"]) && !empty($_POST["FriOpen"]) && !empty($_POST["FriClose"])) {
		$data[] = [true, $_POST["FriOpen"], $_POST["FriClose"]];
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Sat"]) && !empty($_POST["SatOpen"]) && !empty($_POST["SatClose"])) {
		$data[] = [true, $_POST["SatOpen"], $_POST["SatClose"]];
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Sun"]) && !empty($_POST["SunOpen"]) && !empty($_POST["SunClose"])) {
		$data[] = [true, $_POST["SunOpen"], $_POST["SunClose"]];
	} else {
		$data[] = $closed;
	}

} catch (Exception $e) {
    error_log($e->getMessage(), 0);
    html_direct("Sorry, session timed out", "admin.php", 3, false);
    exit();
}

try {
	// Remove old data
	$sql = "DELETE FROM times";
	$conn->query($sql);

	$insert_cmd = $conn->prepare("INSERT INTO times (open, opentime, closetime) VALUES (?, ?, ?)");
	for($i=0;$i<7;$i++) {
		$insert_cmd->bind_param("iss", $data[$i][0], $data[$i][1], $data[$i][2]);
		$insert_cmd->execute();
	}
	header("Location: settimes.php");
} catch(mysqli_sql_exception $e) {
	error_log($e->getMessage(), 0);
	html_direct("Sorry, session timed out", "admin.php", 3, false);
	exit();
} catch (Throwable $t) {
    error_log($t->getMessage(), 0);
    html_direct("Sorry, session timed out", "admin.php", 3, false);
    exit();
}

?>
