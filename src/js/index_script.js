
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

    //guestbook
    document.getElementById("btnGuestbook").addEventListener("click", () => {
        document.getElementById("pageContent").setAttribute("src", "guestbook.php");
    });

    //gallery 
    document.getElementById("btnGallery").addEventListener("click", () => {
        document.getElementById("pageContent").setAttribute("src", "gallery.html");
    });

    //opening times and prices
    document.getElementById("btnTimeNPrice").addEventListener("click", () => {
        document.getElementById("pageContent").setAttribute("src", "time_n_price.html");
    });

    //link to admin login page
    document.getElementById("adminLink").addEventListener("click", () => {
        document.getElementById("pageContent").setAttribute("src", "admin.html");
    });

});

