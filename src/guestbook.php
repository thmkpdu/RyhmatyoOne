<?php

// Set reporting mode to almost all
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

$conf = require "../connection/connect.php";

try {
	// Connect to a mysql/mariadb database
	$conn = new mysqli($conf["host"], $conf["user"], $conf["pass"], $conf["db"]);

	// Set charset for connection
	$conn->set_charset('utf8mb4');

	// Construct SQL query to create a table if it does not exist
	$sql = "CREATE TABLE IF NOT EXISTS entries ";
	$sql .= "(stamp INT UNSIGNED NOT NULL, ";
	$sql .= "name CHAR(255) NOT NULL, ";
	$sql .= "email CHAR(254), "; // Combined all limitations results to 254 chars
	$sql .= "message VARCHAR(1024))";
	$conn->query($sql);

	// If old table is found, alter table to support new timestamps
	$sql = "ALTER TABLE IF EXISTS entries ";
	$sql .= "ADD COLUMN IF NOT EXISTS stamp INT UNSIGNED NOT NULL, ";
	$sql .= "DROP COLUMN IF EXISTS dt";
	$altered = $conn->query($sql);

	// If table was altered set timestamps to current server time
	$now = time();
	$entry_cmd = $conn->prepare("UPDATE entries SET stamp=? WHERE stamp = 0");
	$entry_cmd->bind_param("i", $now);
	$entry_cmd->execute();

} catch (mysqli_sql_exception $e) {
	// HTML stub for error condition
	$ERROR_HTML = '<!DOCTYPE html><html lang="en">';
	$ERROR_HTML .= '<body>';
	$ERROR_HTML .= '<h1 style="text-align:center">Guestbook currently unavailable</h1>';
	$ERROR_HTML .= '</body>';
	$ERROR_HTML .= '</html>';
	echo $ERROR_HTML;
	return;
}

// Catch if user is submitting a new guestbook entry
// and consturct a SQL INSERT statement using user posted values and execute it
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST["name"];
	$email = $_POST["email"];
	$message = $_POST["message"];
	$stamp = intval($_POST["stamp"]);

	if($stamp === 0) {
		header("Location: " . $_SERVER['PHP_SELF']);
		return;
	}

	try {
		$entry_cmd = $conn->prepare("INSERT INTO entries (stamp, name, email, message) VALUES(?, ?, ?, ?)");
		$entry_cmd->bind_param("isss", $stamp, $name, $email, $message);
		$entry_cmd->execute();
	} catch(Throwable) {
		error_log($e->getMessage(),0);
	} finally {
		// Redirect to self to prevent secondary submits on reload
    	header("Location: " . $_SERVER['PHP_SELF']);
    	return;
    }
}

try {
	$sql = "SELECT FROM_UNIXTIME(stamp, '%Y-%m-%d  %h:%i') AS datetime, entries.* FROM entries ORDER BY stamp DESC";
	$data_set = $conn->query($sql);	// Load previous guestbook entries to a variable
} catch(Throwable) {
	$data_set = false; // Make sure variable exists even if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="guestbook">
	<link rel="stylesheet" href="css/guestbook.css">
	<title>Health Fitness Plus's Guestbook</title>
</head>
<body>
	<h1>Our guestbook</h1>
	<div class="container">
		<img src="siteimages/silhouette-small.png">
		<div class="entry">
			<h4>Leave a message to our guestbook</h4>
			<form method="post">
				<!-- Javascript stores user's datetime from browser to a hidden input -->
				<input id="time" type="hidden" name="stamp">
				<div class="name_div">
					<label for="name">Name: </label>
					<input name="name" type=text required>
				</div>
				<div class="email_div">
					<label for="email">Email: </label>
					<input name="email" type=text>
				</div>
				<div class="message_div">
					<label for="message">Message</label>
					<textarea name="message" rows=5></textarea>
				</div>
				<button id="button" type="submit">Submit</button>
			</form>
		</div>
		<img src="siteimages/lifting-small.png">
	</div>
	<div class="entries">
		<!-- Static caption and table headers -->
		<table>
			<caption>Our Visitors</caption>
			<tr>
				<th>Time</th>
				<th>Name</th>
				<th>Email</th>
				<th>Message</th>
			</tr>
			<?php // Guestbook entries dynamically from database
				if($data_set === false) return;
				while($row = $data_set->fetch_assoc()) {
					echo "<tr>";
					echo "<td>" . htmlspecialchars($row['datetime']) . "</td>";
					echo "<td id='name'>" . htmlspecialchars($row['name']) . "</td>";
					echo "<td>" . htmlspecialchars($row['email']) . "</td>";
					echo "<td id='msg'>" . htmlspecialchars($row['message']) . "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
<script src="js/guestbook.js"></script>
</body>
</html>
