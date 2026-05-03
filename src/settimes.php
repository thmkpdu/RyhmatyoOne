<?php
	$conf = require "../connection/connect.php";
	include "session.php";
	session_start();

	// HTML stub for error condition
	$ERROR_HTML = '<!DOCTYPE html><html lang="en">';
	$ERROR_HTML .= '<head><meta http-equiv="refresh" content="3; url=admin.php"></head>';
	$ERROR_HTML .= '<body>';
	$ERROR_HTML .= '<h1 style="text-align:center">Set times page currently unavailable</h1>';
	$ERROR_HTML .= '</body>';
	$ERROR_HTML .= '</html>';

	if(!session_is_valid()) {
		$_SESSION = [];
		session_destroy();
		echo $ERROR_HTML;
		exit();
	}
	$_SESSION["stamp"] = time();

	try {
		// Connect to a mysql/mariadb database
		$conn = new mysqli($conf["host"], $conf["user"], $conf["pass"], $conf["db"]);

		// Set charset for connection
		$conn->set_charset('utf8mb4');

		$sql = "SELECT * FROM times";
		$dataset = $conn->query($sql);
		while($row = $dataset->fetch_assoc()) {
			$data[] = [$row["open"], $row["opentime"], $row["closetime"]];
		}
	} catch(mysqli_sql_exception $e) {
		error_log($e->getMessage(), 0);
	} catch(Throwable $t) {
		error_log($t->getMessage(), 0);
		$dataset = false; // Make sure variable exist
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set times</title>
    <link rel="stylesheet" href="./css/settimes_style.css">
</head>
<body>
	<?php echo "<p>Hello " . $_SESSION["admin"] . ":" . $_SESSION["stamp"] . ":" . session_id() . "</p>";?>
    <div id="header">
        <h1>Set opening and closing times</h1>
        <button id="btnLogout">Log out</button>
    </div>
    <div id="descriptions">
        <p>Is open</p>
        <p>Weekday</p>
        <p>Opening</p>
        <p>Closing</p>
    </div>
    <hr width="100%" size="2" color="black">
    <div id="weekdayInputs">
        <form method="post" id="weekdayForm">
            <div id="divMon" class="dayDiv">
                <input type="checkbox" id="chbMon" name="Mon" <?php if($dataset) echo $data[0][0] ? "checked":""; ?>>
                <p class="theDay">Monday</p>
                <input name="MonOpen" type=time <?php if($dataset) echo '"' . $data[0][1] . '"';?>>
                <input name="MonClose" type=time <?php if($dataset) echo '"' . $data[0][2] . '"';?>>
            </div>
            <div id="divTue" class="dayDiv">
                <input type="checkbox" id="chbTue" name="Tue" <?php if($dataset) echo $data[1][0] ? "checked":""; ?>>
                <p class="theDay">Tuesday</p>
                <input name="TueOpen" type=time <?php if($dataset) echo '"' . $data[1][1] . '"';?>>
                <input name="TueClose" type=time <?php if($dataset) echo '"' . $data[1][2] . '"';?>>
            </div>
            <div id="divWed" class="dayDiv">
                <input type="checkbox" id="chbWed" name="Wed" <?php if($dataset) echo $data[2][0] ? "checked":""; ?>>
                <p class="theDay">Wednesday</p>
                <input name="WedOpen" type=time <?php if($dataset) echo '"' . $data[2][1] . '"';?>>
                <input name="WedClose" type=time <?php if($dataset) echo '"' . $data[2][2] . '"';?>>
            </div>
            <div id="divThu" class="dayDiv">
                <input type="checkbox" id="chbThu" name="Thu" <?php if($dataset) echo $data[3][0] ? "checked":""; ?>>
                <p class="theDay">Thursday</p>
                <input name="ThuOpen" type=time <?php if($dataset) echo '"' . $data[3][1] . '"';?>>
                <input name="ThuClose" type=time <?php if($dataset) echo '"' . $data[3][2] . '"';?>>
            </div>
            <div id="divFri" class="dayDiv">
                <input type="checkbox" id="chbFri" name="Fri" <?php if($dataset) echo $data[4][0] ? "checked":""; ?>>
                <p class="theDay">Friday</p>
                <input name="FriOpen" type=time <?php if($dataset) echo '"' . $data[4][1] . '"';?>>
                <input name="FriClose" type=time <?php if($dataset) echo '"' . $data[4][2] . '"';?>>>
            </div>
            <div id="divSat" class="dayDiv">
                <input type="checkbox" id="chbSat" name="Sat" <?php if($dataset) echo $data[5][0] ? "checked":""; ?>>
                <p class="theDay">Saturday</p>
                <input name="SatOpen" type=time <?php if($dataset) echo '"' . $data[5][1] . '"';?>>
                <input name="SatClose" type=time <?php if($dataset) echo '"' . $data[5][2] . '"';?>>
            </div>
            <div id="divSun" class="dayDiv">
                <input type="checkbox" id="chbSun" name="Sun" <?php if($dataset) echo $data[6][0] ? "checked":""; ?>>
                <p class="theDay">Sunday</p>
                <input name="SunOpen" type=time <?php if($dataset) echo '"' . $data[6][1] . '"';?>>
                <input name="SunClose" type=time <?php if($dataset) echo '"' . $data[6][2] . '"';?>>
            </div>
            <button id="btnOk">OK</button>
            <button id="btnCancel">Cancel</button>
        </form>
    </div>
</body>
</html>
