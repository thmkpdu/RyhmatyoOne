<?php
// Set reporting mode to almost all
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

$conf = require "../connection/connect.php";
$conn = new mysqli($conf["host"], $conf["user"], $conf["pass"], $conf["db"]);

// Exit if connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset for connection
$conn->set_charset('utf8mb4');

// Construct SQL query to create a table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS entries ";
$sql .= "(dt CHAR(29) NOT NULL, "; // Javascript UTC datetime takes up to 29 chars
$sql .= "name CHAR(255) NOT NULL, ";
$sql .= "email CHAR(254), "; // Combined all limitations results to 254 chars
$sql .= "message VARCHAR(1024))";

// Create table or exit if creation fails
$result = $conn->query($sql);
if($result === false) {
	die("Something went wrong: " .  $conn->error);
}

// Catch if file was reloaded when user submitted a new guestbook entry
// and consturct a SQL INSERT statement using user posted values and execute it
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$dt = $_POST["datetime"];
	$name = $_POST["name"];
	$email = $_POST["email"];
	$message = $_POST["message"];

	$entry_cmd = $conn->prepare("INSERT INTO entries VALUES(?, ?, ?, ?)");
	$entry_cmd->bind_param("ssss", $dt, $name, $email, $message);
	if( ! $entry_cmd->execute() ) {
		die("Saving guestbook entry failed: " . $conn->error);
	}

	// Redirect to self to prevent secondary submits on reload
	header("Location: " . $_SERVER['PHP_SELF']);

	// No need to process rest of file after redirection to self
	exit();
}

// Load previous guestbook entries to a variable or exit on failure
$sql = "SELECT * FROM entries";
$data_set = $conn->query($sql);
if($data_set === false) {
	die("Something went wrong: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="guestbook">
	<title>Health Fitness Plus's Guestbook</title>
</head>
<body>
	<div class="entry">
		<form method="post">
			<!-- Javascript stores user's datetime from browser to a hidden input -->
			<input id="time" type=text name="datetime" hidden>
			<label for="name">Name: </label>
			<input id="name" name="name" type=text required>
			<label for="email">Email: </label>
			<input id="email" name="email" type=text>
			<label for="message">Message</label>
			<textarea id="message" name="message" rows=5></textarea>
			<button id="button" type="submit">Submit</button>
		</form>
	</div>
	<div class="entries">
		<!-- Static caption and table headers -->
		<table>
			<caption>Guestbook entries</caption>
			<tr>
				<th>Time</th>
				<th>Name</th>
				<th>Email</th>
				<th>Message</th>
			</tr>
			<?php // Guestbook entries dynamically from database
				while($row = $data_set->fetch_assoc()) {
					echo "<tr>";
					echo "<td>" . htmlspecialchars($row['dt']) . "</td>";
					echo "<td>" . htmlspecialchars($row['name']) . "</td>";
					echo "<td>" . htmlspecialchars($row['email']) . "</td>";
					echo "<td>" . htmlspecialchars($row['message']) . "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
<script src="js/guestbook.js"></script>
</body>
</html>
