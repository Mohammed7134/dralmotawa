
// Add an event listener for the form submission
function handleFormSubmit(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target; // Get the form element that triggered the event

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
            const textarea = document.getElementById(`textarea-${form.wisdomId.value}`);
            textarea.value = data.text;
            await showSnackbar(data.message);
        } else {
            await showSnackbar(data.message);
        }
    }).catch((error) => {
        // Handle errors that occurred during the AJAX request
        console.error('Error:', error);
    });
};

// Attach the event listener to all forms
function addChangingTextFunctionality() {
    document.querySelectorAll('.edit-form').forEach((form) => {
        form.addEventListener('submit', handleFormSubmit);
        // Add other events as needed, e.g., 'change', 'input', etc.
    });
}
addChangingTextFunctionality();