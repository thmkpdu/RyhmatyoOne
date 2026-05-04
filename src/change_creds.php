<?php
include "session.php";
session_start();
if(session_is_valid()) $_SESSION["stamp"] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/change_creds.css">
    <title>Change credentials</title>
</head>
<body>
	<?php echo "<p>Hello " . $_SESSION["admin"] . ":" . $_SESSION["stamp"] . ":" . session_id() . "</p>";?>
    <h1>Change admin credentials</h1>
    <div class="container">
        <form action="save_creds.php" method="post">
			<div class="shadow">
				<input type="text" name="username" autocomplete="off" aria-hidden="true" tabindex="-1">
			</div>
			<div class="item">
				<label for="old_user">Old username: </label>
				<input name="old_user" type=text required>
			</div>
			<div class="item">
				<label for="user">New username: </label>
				<input name="user" type=text required>
			</div>
			<div class="item">
				<label for="re_user">Retype new username: </label>
				<input name="re_user" type=text required>
			</div>
			<div class="item">
				<label for="old_passw">Old password: </label>
				<input name="old_passw" type=text required>
			</div>
			<div class="item">
				<label for="passw">New password: </label>
				<input name="passw" type=text required>
			</div>
			<div class="item">
				<label for="re_passw">Retype new password: </label>
				<input name="re_passw" type=text required>
			</div>
			<div class="buttons">
				<button type="submit">Save</button>
				<button onclick="window.location.href='logout.php'">Cancel</button>
			</div>
		</form>
    </div>
</body>
</html>
