// Function to get all selected options
function getSelectedOptions(element) {
    var selectElement = document.querySelector(`.selectMenu-${element.value.split("-")[0]}`);
    const selectedOptions = [];
    const options = selectElement.options;

    for (let i = 0; i < options.length; i++) {
        if (options[i].selected) {
            selectedOptions.push(options[i].value.split("-")[1]);
        }
    }

    return selectedOptions;
}

function logValue(element) {
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