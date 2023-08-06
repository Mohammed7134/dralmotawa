function changeWisdomText(element) {
    const selectedOptions = getSelectedOptions(element);
    console.dir(selectedOptions);
    if (element.value) {
        data = {
            '_token': $('meta[name=csrf-token]').attr('content'),
            wisdomId: element.value.split("-")[0],
            updatedCategories: selectedOptions
        }
        fetch('/changeCategory', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        }).then(res => {
            return res.json();
        }).then(async data => {
            if (data.error === false) {
                await showSnackbar("تم تعديل التصنيف");
            } else {
                await showSnackbar("لم يتم التعديل");
            }
        })
    } else {
        showSnackbar("حدث خطأ ما!");
    }
}
// Get a reference to the form element
const form = document.getElementById('edit-form');

// Add an event listener for the form submission
form.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent the default form submission

    // Get the form data
    const formData = new FormData(form);

    // Make an AJAX request using fetch
    fetch('/changeText', {
        method: 'POST', // or 'GET' if your server expects GET
        body: formData,
    }).then(res => {
        return res.json();
    }).then(async data => {
        if (data.error === false) {
            const textarea = document.getElementById('myTextarea');
            textarea.value = data.text;
            await showSnackbar(data.message);
        } else {
            await showSnackbar(data.message);
        }
    }).catch((error) => {
        // Handle errors that occurred during the AJAX request
        console.error('Error:', error);
    });
});
