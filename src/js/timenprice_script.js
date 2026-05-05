async function getData() {
    try {
        const response = await fetch("pick_times.php?echo");
        if(!response.ok){
            throw new Error("Failed to get data" + response.status);
        }
        return  await response.json();
    } catch(error) {
        console.log(error);
        return null;
    }
}

//when document loads:
document.addEventListener("DOMContentLoaded", async () => {
    let times = await getData();
    let wkDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    //Set day closed using isOpen value from getData
    for (let i = 0; i < 7; i++) {
        let is_open = times[i][0];
        let dayName = wkDays[i];
        if (parseInt(is_open) == 0) {
            document.getElementById("day"+dayName).textContent = `${dayName} : Closed`;
        }
	}

	//go through days and set day 'unclear' if time 00:00 to 00:00
    wkDays.forEach((item) => {
		//get the day's text
		let dayText = document.getElementById("day"+item).textContent;
		//count how many zeroes occur
		let counts = dayText.match(/0/g);
		//on 8 zeroes, set day text to unclear
		if (couts && counts.length === 8) {
			document.getElementById("day"+item).textContent = `${item} : Unclear, please call headquarters.`;
		}	
	});
       
        

    // Create objects for opening and closing times
    let opentime = new Date();
    let closetime = new Date();
    let current_time = new Date();

    // Get day number 0-6
    let day = current_time.getDay();

    let today_open = "";
    let today_close = "";
    if(times) {
            // grab open and close times
            today_open = times[day][1];
            today_close = times[day][2];
    } else {
            // Some values if getting data fails
            today_open = "00:00";
            today_close = "00:00";
    }

    // Split times
    let open = today_open.split(":");
    let close = today_close.split(":");

    // Set hours, minutes and seconds
    opentime.setHours(open[0]);
    opentime.setMinutes(open[1]);
    opentime.setSeconds("00");
    closetime.setHours(close[0]);
    closetime.setMinutes(close[1]);
    closetime.setSeconds("00");

    //get current system hours and minutes
    let hours = current_time.getHours();
    let minutes = current_time.getMinutes();

    //if current hours inside the given opening and closing times, show message
    if (current_time > opentime && current_time < closetime) {
        //add zero id minutes is less than 10 to show clock time correctly
        let string_mins = (minutes < 10) ? "0"+String(minutes) : String(minutes);
        document.getElementById("isopen").textContent = `YOUR LOCAL HEALTH FITNESS PLUS GYM IS CURRENTLY (${hours}:${string_mins}) OPEN!`;
    }
});
