<?php
include "session.php";
session_start();

if(session_is_valid()) {
	// Activity update
	$_SESSION["stamp"] = time();

	// Session is valid, no need to authenticate again.
	// Redirect to set times page when session is valid.
	header("Location: settimes.php");

	// Nothing to do here after redirect
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin login</title>
    <link rel="stylesheet" href="./css/adlog_style.css">
</head>
<body>
	<?php echo "<p>Hello " . $_SESSION["admin"] . ":" . $_SESSION["stamp"] . ":" . session_id() . "</p>";?>
    <h1>Admin login</h1>
    <div class="loginBox">
        <form action="auth.php" method="post">
			<div class="shadow">
				<input type="text" name="username" autocomplete="off" aria-hidden="true" tabindex="-1">
			</div>
			<div class="user_div">
				<label for="user">User: </label>
				<input name="user" type=text required>
			</div>
			<div class="passw_div">
				<label for="passw">Password: </label>
				<input name="passw" type=text required>
			</div>
			<button id="btnLogin" type="submit">Log in</button>
		</form>
    </div>
	<a href="settimes.php">Temporary link to see the settings page, remove later.</a>
    <script src="./js/adlog_script.js"></script>
</body>
</html>
