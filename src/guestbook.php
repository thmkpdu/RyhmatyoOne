<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Vieraskirja">
	<title>Vieraskirja</title>
</head>
<body>
	<div class="entry">
		<form action="save_entry.php" method="post">
			<input type=text name="datetime" hidden>
			<label for="name">Nimesi: </label>
			<input id="name" name="name" type=text required>
			<label for="email">Sähköposti: </label>
			<input id="email" name="email" type=text>
			<label for="message"></label>
			<textarea id="message" name="message" rows=5></textarea>
			<button type="submit">Lähetä</button>
		</form>
	</div>
	<div class="entries">
		<table>
			<caption>Sivulla vierailleet</caption>
			<tr>
				<th>Aika</th>
				<th>Nimi</th>
				<th>Email</th>
				<th>Viesti</th>
			</tr>
		</table>
	</div>
</body>
</html>
