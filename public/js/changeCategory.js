function logValue(element) {
    var selected = [];
    var selectedNames = {};
    if (document.querySelector(`.selectMenu-${element.value.split("-")[0]}`)) {
        var options = document.querySelector(`.selectMenu-${element.value.split("-")[0]}`).options;
        for (var option of options) {
            if (option.selected) {
                selected.push(option.value.split("-")[1]);
                selectedNames[option.value.split("-")[1]] = option.innerText;
            }
        }
        if (element.value) {
            data = {
                '_token': $('meta[name=csrf-token]').attr('content'),
                wisdomId: element.value.split("-")[0],
                newCategories: selected
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
                    await showSnackbar("data.message");
                }
            })
        } else {
            showSnackbar("errorText");
        }
    } else {
        showSnackbar("errorText");
    }
}