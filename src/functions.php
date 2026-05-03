<?php
// Echoes message $msg
// redirects to $url after $timeout
// if $full_load is set to true uses javascript to make full reload 
function html_direct($msg, $url, $timeout, $full_load) {
	// HTML
	$HTML = '<!DOCTYPE html><html lang="en">';
	$HTML .= '<head>';
	if(!$full_load) {
		$HTML .= "<meta http-equiv='refresh' content='$timeout; url=$url'>";
	}
	$HTML .= '</head>';
	$HTML .= '<body>';
	$HTML .= "<h1 style='text-align:center'>$msg</h1>";
	if($full_load) {
		$timeout = $timeout * 1000; // JavaScript takes milliseconds
		$HTML .= "<script>setTimeout(() => {window.top.location.href='$url';}, $timeout);</script>";
	}
	$HTML .= '</body>';
	$HTML .= '</html>';

	echo $HTML;
}
?>
