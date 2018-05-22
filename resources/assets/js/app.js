$(document).ready(() => {

    // NAVIGATION POPUP
    $("#header").on("click", () => {
        $("#navbar").toggleClass("show");
    });

    // ADMIN LISTING
    $(".toggleable").on("click", (e) => {
        $(e.currentTarget).toggleClass("show")
    })
});