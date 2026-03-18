
//when document loads:
document.addEventListener("DOMContentLoaded", () => {
    //set first to fp_info.html as in 'front page info'
    document.getElementById("pageContent").setAttribute("src", "fp_info.html");
    
    //adding listeners to buttons, set attribute to the path of the html file directed to
    //first page info
    document.getElementById("btnFrontPage").addEventListener("click", () => {
        document.getElementById("pageContent").setAttribute("src", "fp_info.html");
    });
    
    //info
    document.getElementById("btnInfo").addEventListener("click", () => {
        document.getElementById("pageContent").setAttribute("src", "info.html");
    });

    //guestbook - to be added
    document.getElementById("btnGuestbook").addEventListener("click", () => {
        //for opening the guestbook under the index navigation
        document.getElementById("pageContent").setAttribute("src", "guestbook.php");

        //code for going completely to the wanted page instead of opening under index navigation
        //can apply if pre used code doesn't work well
        //window.location.href = "guestbook.php";
    });

    //gallery 
    document.getElementById("btnGallery").addEventListener("click", () => {
         
        document.getElementById("pageContent").setAttribute("src", "gallery.html");


    });


});

