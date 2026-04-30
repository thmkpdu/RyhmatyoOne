<?php
include "session.php";
session_start();

$CONF_HTML = '<!DOCTYPE html><html lang="en">';
$CONF_HTML .= '<body>';
$CONF_HTML .= '<h1 style="text-align:center">Succesfully logged out</h1>';
$CONF_HTML .= '<script>setTimeout(() => {window.top.location.href = "index.html";}, 3000);</script>';
$CONF_HTML .= '</body>';
$CONF_HTML .= '</html>';

$_SESSION = [];
session_destroy();

if(!session_is_valid()) {
	echo $CONF_HTML;
	exit();
}
?>
