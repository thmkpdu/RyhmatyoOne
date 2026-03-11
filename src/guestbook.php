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
		<form action="save_entry.php" method="post">
			<input type=text name="datetime" hidden>
			<label for="name">Name: </label>
			<input id="name" name="name" type=text required>
			<label for="email">Email: </label>
			<input id="email" name="email" type=text>
			<label for="message">Message</label>
			<textarea id="message" name="message" rows=5></textarea>
			<button type="submit">Submit</button>
		</form>
	</div>
	<div class="entries">
		<table>
			<caption>Guestbook entries</caption>
			<tr>
				<th>Time</th>
				<th>Name</th>
				<th>Email</th>
				<th>Message</th>
			</tr>
		</table>
	</div>
</body>
</html>
