window.onscroll = function () { stickNavigationBar() };

var navbar = document.querySelector(".navbar");
var sticky = navbar.offsetTop;

function stickNavigationBar() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky")
    } else {
        navbar.classList.remove("sticky");
    }
}