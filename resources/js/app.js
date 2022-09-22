// Import our custom CSS
import '../sass/app.scss'

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap'

//Setting up sidebar functionality
function togglingActions() {
    $('#sidebar').toggleClass('active');
    $('#sidebar').toggleClass('inactive');
    $('#xmark').toggleClass('show');
    $('#content').toggleClass('inactive');
    $('body').toggleClass('scroll-disable');
}
function sidebarFontControlFunctionality() {
    $(document).ready(function () {
        $('#sidebarCollapse, #xmark').on('click', function (e) {
            e.stopPropagation();
            togglingActions();
        });
        $('#sidebar').on('click', function (e) {
            e.stopPropagation();
        });
        $('body,html').on('click', function () {
            if (document.querySelector("#xmark").classList.contains("show")) {
                togglingActions();
            }
        });

    });
}
sidebarFontControlFunctionality();
