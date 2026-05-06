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
	html_direct($e->getMessage(), "admin.php", 3, false);
	exit();
}

// Pick values for SQL statements from POSTED values
try {
    if($_SERVER["REQUEST_METHOD"] !== "POST") throw new Exception("Unexpected HTTP request");

    $no_data = [false, "00:00", "00:00"];

	if(!empty($_POST["MonOpen"]) && !empty($_POST["MonClose"])) {
		if($_POST["MonOpen"] === $_POST["MonClose"]) {
			html_direct("Refused to save. Monday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["MonOpen"], $_POST["MonClose"])) {
			html_direct("Refused to save. Monday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Mon"]);
			$data[] = [$open, $_POST["MonOpen"], $_POST["MonClose"]];
		}
	} else {
		$data[] = $no_data;
	}

	if(!empty($_POST["TueOpen"]) && !empty($_POST["TueClose"])) {
		if($_POST["TueOpen"] === $_POST["TueClose"]) {
			html_direct("Refused to save. Tuesday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["TueOpen"], $_POST["TueClose"])) {
			html_direct("Refused to save. Tuesday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Tue"]);
			$data[] = [$open, $_POST["TueOpen"], $_POST["TueClose"]];
		}
	} else {
		$data[] = $no_data;
	}

	if(!empty($_POST["WedOpen"]) && !empty($_POST["WedClose"])) {
		if($_POST["WedOpen"] === $_POST["WedClose"]) {
			html_direct("Refused to save. Wednesday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["WedOpen"], $_POST["WedClose"])) {
			html_direct("Refused to save. Wednesday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Wed"]);
			$data[] = [$open, $_POST["WedOpen"], $_POST["WedClose"]];
		}
	} else {
		$data[] = $no_data;
	}

	if(!empty($_POST["ThuOpen"]) && !empty($_POST["ThuClose"])) {
		if($_POST["ThuOpen"] === $_POST["ThuClose"]) {
			html_direct("Refused to save. Thursday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["ThuOpen"], $_POST["ThuClose"])) {
			html_direct("Refused to save. Thursday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Thu"]);
			$data[] = [$open, $_POST["ThuOpen"], $_POST["ThuClose"]];
		}
	} else {
		$data[] = $no_data;
	}

	if(!empty($_POST["FriOpen"]) && !empty($_POST["FriClose"])) {
		if($_POST["FriOpen"] === $_POST["FriClose"]) {
			html_direct("Refused to save. Friday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["FriOpen"], $_POST["FriClose"])) {
			html_direct("Refused to save. Friday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Fri"]);
			$data[] = [$open, $_POST["FriOpen"], $_POST["FriClose"]];
		}
	} else {
		$data[] = $no_data;
	}

	if(!empty($_POST["SatOpen"]) && !empty($_POST["SatClose"])) {
		if($_POST["SatOpen"] === $_POST["SatClose"]) {
			html_direct("Refused to save. Saturday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["SatOpen"], $_POST["SatClose"])) {
			html_direct("Refused to save. Saturday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Sat"]);
			$data[] = [$open, $_POST["SatOpen"], $_POST["SatClose"]];
		}
	} else {
		$data[] = $no_data;
	}

	if(!empty($_POST["SunOpen"]) && !empty($_POST["SunClose"])) {
		if($_POST["SunOpen"] === $_POST["SunClose"]) {
			html_direct("Refused to save. Sunday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} elseif(is_later($_POST["SunOpen"], $_POST["SunClose"])) {
			html_direct("Refused to save. Sunday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		} else {
			$open = isset($_POST["Sun"]);
			$data[] = [$open, $_POST["SunOpen"], $_POST["SunClose"]];
		}
	} else {
		$data[] = $no_data;
	}
} catch (Exception $e) {
    error_log($e->getMessage(), 0);
    session_clean_up();
    html_direct($e->getMessage(), "admin.php", 3, false);
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
	html_direct($e->getMessage(), "admin.php", 3, false);
	exit();
} catch (Throwable $t) {
    error_log($t->getMessage(), 0);
    html_direct($e->getmessage(), "admin.php", 3, false);
    exit();
}

?>
