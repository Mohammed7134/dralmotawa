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
        // var hashtags = document.querySelectorAll(`.hashtag.w${element.value.split("-")[0]}`);
        // var hashtagArea = document.querySelector(`.hashtags-area-${element.value.split("-")[0]}`)
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
                    await showSnackbar("successText");
                    // for (let i = 0; i < hashtags.length; i++) {
                    //     hashtags[i].remove();
                    // }
                    // for (const [key, value] of Object.entries(selectedNames)) {
                    //     hashtagArea.innerHTML += `<a type="button" href="/exploring?categoryId=${key}" class="hashtag ${key} ${element.value.split("-")[0]}-${key} w${element.value.split("-")[0]}" style="padding: 0vw 3vw;font-size: 18px;">${value}</a>`
                    // }
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