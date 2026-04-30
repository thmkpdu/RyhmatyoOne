<?php

$timeout = 60;
$user_hash = '$2y$15$0n82r6J.ZfOwcnZIK/dvbO9JsMlBkR.rG0bmPcgbGd4vNnkeY6v8W';
$pass_hash = '$2y$15$1FSwxnAeM83EsYOQhFvWD.o32wnnC3nUhiiwAu66CpoUG38r0inCW';

function session_is_valid() {
	global $timeout;

	if(empty($_SESSION["admin"])) {
		$_SESSION["admin"] = "";
		$_SESSION["stamp"] = "";
		return false;
	}

	if(empty($_SESSION["stamp"]) || time() - $_SESSION["stamp"] > $timeout) {
		$_SESSION["admin"] = "";
		$_SESSION["stamp"] = "";
		return false;
	}

	return true;
}
?>
