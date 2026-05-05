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

    $closed = [false, "00:00", "00:00"];

	if(!empty($_POST["Mon"]) && !empty($_POST["MonOpen"]) && !empty($_POST["MonClose"])) {
		if(is_later($_POST["MonClose"], $_POST["MonOpen"])) {
			$data[] = [true, $_POST["MonOpen"], $_POST["MonClose"]];
		} elseif($_POST["MonOpen"] === $_POST["MonClose"]) {
			html_direct("Refused to save. Monday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Monday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Tue"]) && !empty($_POST["TueOpen"]) && !empty($_POST["TueClose"])) {
		if(is_later($_POST["TueClose"], $_POST["TueOpen"])) {
			$data[] = [true, $_POST["TueOpen"], $_POST["TueClose"]];
		} elseif($_POST["TueOpen"] === $_POST["TueClose"]) {
			html_direct("Refused to save. Tuesday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Tueday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Wed"]) && !empty($_POST["WedOpen"]) && !empty($_POST["WedClose"])) {
		if(is_later($_POST["WedClose"], $_POST["WedOpen"])) {
			$data[] = [true, $_POST["WedOpen"], $_POST["WedClose"]];
		} elseif($_POST["WedOpen"] === $_POST["WedClose"]) {
			html_direct("Refused to save. Wednesday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Wednesday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Thu"]) && !empty($_POST["ThuOpen"]) && !empty($_POST["ThuClose"])) {
		if(is_later($_POST["ThuClose"], $_POST["ThuOpen"])) {
			$data[] = [true, $_POST["ThuOpen"], $_POST["ThuClose"]];
		} elseif($_POST["ThuOpen"] === $_POST["ThuClose"]) {
			html_direct("Refused to save. Thurday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Thursday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Fri"]) && !empty($_POST["FriOpen"]) && !empty($_POST["FriClose"])) {
		if(is_later($_POST["FriClose"], $_POST["FriOpen"])) {
			$data[] = [true, $_POST["FriOpen"], $_POST["FriClose"]];
		} elseif($_POST["FriOpen"] === $_POST["FriClose"]) {
			html_direct("Refused to save. Friday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Friday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Sat"]) && !empty($_POST["SatOpen"]) && !empty($_POST["SatClose"])) {
		if(is_later($_POST["SatClose"], $_POST["SatOpen"])) {
			$data[] = [true, $_POST["SatOpen"], $_POST["SatClose"]];
		} elseif($_POST["SatOpen"] === $_POST["SatClose"]) {
			html_direct("Refused to save. Saturday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Saturday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
	}

	if(!empty($_POST["Sun"]) && !empty($_POST["SunOpen"]) && !empty($_POST["SunClose"])) {
		if(is_later($_POST["SunClose"], $_POST["SunOpen"])) {
			$data[] = [true, $_POST["SunOpen"], $_POST["SunClose"]];
		} elseif($_POST["SunOpen"] === $_POST["SunClose"]) {
			html_direct("Refused to save. Sunday's opening time and closing time are the same", "settimes.php", 3, false);
			exit();
		} else {
			html_direct("Refused to save. Sunday's opening time is later than closing time", "settimes.php", 3, false);
			exit();
		}
	} else {
		$data[] = $closed;
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
