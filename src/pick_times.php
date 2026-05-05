<?php
try {
	$driver = new mysqli_driver();
	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

	$conf = require "../connection/connect.php";

	// Connect to a mysql/mariadb database
	$conn = new mysqli($conf["host"], $conf["user"], $conf["pass"], $conf["db"]);

	// Set charset for connection
	$conn->set_charset('utf8mb4');

	$sql = "SELECT * FROM times";
	$dataset = $conn->query($sql);
} catch(mysqli_sql_exception $e) {
	error_log($_SERVER['PHP_SELF'] . ": " . $e->getMessage());
}

try {
	if(!isset($dataset)) throw new Exception("$dataset undefined");

	$dataset->data_seek(6);
	$row = $dataset->fetch_row();
	$SunOpen = (bool) $row[0];
	$SunOpenTime = $row[1];
	$SunCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

	$dataset->data_seek(0);
	$row = $dataset->fetch_row();
	$MonOpen = (bool) $row[0];
	$MonOpenTime = $row[1];
	$MonCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

	$dataset->data_seek(1);
	$row = $dataset->fetch_row();
	$TueOpen = (bool) $row[0];
	$TueOpenTime = $row[1];
	$TueCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

	$dataset->data_seek(2);
	$row = $dataset->fetch_row();
	$WedOpen = (bool) $row[0];
	$WedOpenTime = $row[1];
	$WedCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

	$dataset->data_seek(3);
	$row = $dataset->fetch_row();
	$ThuOpen = (bool) $row[0];
	$ThuOpenTime = $row[1];
	$ThuCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

	$dataset->data_seek(4);
	$row = $dataset->fetch_row();
	$FriOpen = (bool) $row[0];
	$FriOpenTime = $row[1];
	$FriCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

	$dataset->data_seek(5);
	$row = $dataset->fetch_row();
	$SatOpen = (bool) $row[0];
	$SatOpenTime = $row[1];
	$SatCloseTime = $row[2];
	$open_times[] = [$row[0], $row[1], $row[2]];

} catch(Exception $e) {
	$open_times[] = [false, "00:00", "00:00"];
	$open_times[] = [false, "00:00", "00:00"];
	$open_times[] = [false, "00:00", "00:00"];
	$open_times[] = [false, "00:00", "00:00"];
	$open_times[] = [false, "00:00", "00:00"];
	$open_times[] = [false, "00:00", "00:00"];
	$open_times[] = [false, "00:00", "00:00"];
}

if(isset($_GET["echo"])) {
	header('Content-Type: application/json');
	echo json_encode($open_times);
}
?>
