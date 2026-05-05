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

// Takes 2 time strings in format HH:MM
// Retruns true if $a is later than $b
function is_later($a, $b) {
	// Split time strings and type cast to int
	$a_hour = (int) substr($a, 0, 2);
	$a_minute = (int) substr($a, 3, 2);

	$b_hour = (int) substr($b, 0, 2);
	$b_minute = (int) substr($b, 3, 2);

	if($a_hour > $b_hour) return true;
	if($a_hour === $b_hour && $a_minute > $b_minute) return true;
	if($a === $b) return true;

	return false;
}
?>
