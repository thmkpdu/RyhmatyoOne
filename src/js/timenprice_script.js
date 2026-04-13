
//when document loads:
document.addEventListener("DOMContentLoaded", () => {
    //opening times sun - sat
    let open_times = [[9, 18],[6, 21],[6, 21],[6, 21],[6, 21],[6, 21],[9, 20],]

    //get system time info
    let current_time = new Date();
    //get current system hours and minutes
    let hours = current_time.getHours();
    let minutes = current_time.getMinutes();
    //get current date index
    let today_day = current_time.getDay();

    //fetch day's opening and closing times from the list
    let todays_open = open_times[today_day];

    //if current hours inside the given opening and closing times, show message
    if (hours >= todays_open[0] && hours < todays_open[1]) {
        //add zero id minutes is less than 10 to show clock time correctly
        let string_mins = (minutes < 10) ? "0"+String(minutes) : String(minutes);
        document.getElementById("isopen").textContent = `YOUR LOCAL HEALTH FITNESS PLUS GYM IS CURRENTLY (${hours}:${string_mins}) OPEN!`;
    }
    
});