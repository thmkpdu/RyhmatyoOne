const button = document.getElementById("button");

/*
* Get time from browser, convert it to a UNIX timestamp and
* store it to a hidden input's value before POST happens
*/
button.addEventListener("click", function (event) {
	const stamp = document.getElementById("time");
	stamp.value = Math.floor(Date.now() / 1000);
});
