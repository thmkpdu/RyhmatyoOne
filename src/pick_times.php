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
	$SunOpen = $row[0];
	$SunOpenTime = $row[1];
	$SunCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

	$dataset->data_seek(0);
	$row = $dataset->fetch_row();
	$MonOpen = $row[0];
	$MonOpenTime = $row[1];
	$MonCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

	$dataset->data_seek(1);
	$row = $dataset->fetch_row();
	$TueOpen = $row[0];
	$TueOpenTime = $row[1];
	$TueCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

	$dataset->data_seek(2);
	$row = $dataset->fetch_row();
	$WedOpen = $row[0];
	$WedOpenTime = $row[1];
	$WedCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

	$dataset->data_seek(3);
	$row = $dataset->fetch_row();
	$ThuOpen = $row[0];
	$ThuOpenTime = $row[1];
	$ThuCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

	$dataset->data_seek(4);
	$row = $dataset->fetch_row();
	$FriOpen = $row[0];
	$FriOpenTime = $row[1];
	$FriCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

	$dataset->data_seek(5);
	$row = $dataset->fetch_row();
	$SatOpen = $row[0];
	$SatOpenTime = $row[1];
	$SatCloseTime = $row[2];
	$open_times[] = [$row[1], $row[2]];

} catch(Exception $e) {
	$SunOpen = 0;
	$open_times[] = ["00:00", "00:00"];

	$MonOpen = 0;
	$open_times[] = ["00:00", "00:00"];

	$TueOpen = 0;
	$open_times[] = ["00:00", "00:00"];

	$WedOpen = 0;
	$open_times[] = ["00:00", "00:00"];

	$ThuOpen = 0;
	$open_times[] = ["00:00", "00:00"];

	$FriOpen = 0;
	$open_times[] = ["00:00", "00:00"];

	$SatOpen = 0;
	$open_times[] = ["00:00", "00:00"];
}

if(isset($_GET["echo"])) {
	header('Content-Type: application/json');
	echo json_encode($open_times);
}
?>
