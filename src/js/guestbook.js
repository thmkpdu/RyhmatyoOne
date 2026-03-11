const dt = document.getElementById("time");
const button = document.getElementById("button");

button.addEventListener("click", function (event) {
	dt.value = new Date().toISOString();
});
