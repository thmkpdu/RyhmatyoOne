<?php
include "functions.php";
include "session.php";
session_start();
session_clean_up();
session_destroy();

if(!session_is_valid()) {
	html_direct("Succesfully logged out", "index.html", 2, true);
	exit();
}
?>
