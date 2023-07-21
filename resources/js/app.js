// Import all of Bootstrap's JS
import './bootstrap';
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

jQuery(window).ready(startCounter);
function startCounter() {
    jQuery('.love_count').each(function () {
        var $this = jQuery(this);
        jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
            duration: 4000,
            easing: 'swing',
            step: function () {
                $this.text(Math.ceil(this.Counter));
            }
        });
    });
}

// Get all the links and desired forms on the page
const links = document.querySelectorAll('a');
const searchForm = document.getElementById('search-form');
const editForm = document.getElementById('edit-form');
const addForm = document.getElementById('add-form');

// Define the function to be executed on click
const handleClick = (event) => {
    showSnackbar("جاري التحميل...");
};

// Attach the event listener to each link
links.forEach((link) => {
    if (link.id != "whatsapp-link") {
        link.addEventListener('click', handleClick);
    }
});

// Attach the event listener to the form(s)
searchForm.addEventListener('submit', handleClick);
if (editForm) {
    editForm.addEventListener('submit', handleClick);
}
if (addForm) {
    addForm.addEventListener('submit', handleClick);
}

// Filter by category

// Get the select element
const categorySelect = document.getElementById('category-select');

// Add an event listener to the select element
categorySelect.addEventListener('change', function () {
    // Get the form and submit it
    const form = document.getElementById('filterForm');
    form.submit();
    handleClick();
});


