function buttonPressed(e) {
    const blogPostContainer = document.querySelectorAll(".blog_post_container");
    for (post of blogPostContainer) {
        post.style.display = "none";
    }
    const wisdomId = e.id.split("-")[1];
    const displayedWisdom = document.querySelector(`.displayed_wisdom_${wisdomId}`);
    const displayedWisdomDiv = document.querySelector(`.short_container_${wisdomId}`);
    displayedWisdomDiv.style.display = "";
    displayedWisdomDiv.scrollIntoView();
    const fullText = document.getElementById(wisdomId).innerHTML;
    if (displayedWisdom) {
        displayedWisdom.innerHTML = fullText.replace("<br>", "");
    }
    const categoriesDiv = document.querySelector('.categories');
    const categories = e.id.split("-")[0];
    let ConvertStringToHTML = function (str) {
        let parser = new DOMParser();
        let doc = parser.parseFromString(str, 'text/html');
        return doc.body;
    };
    fetch('/json/categories.json')
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            if (fullText) {
                categoriesDiv.innerHTML = "";
                JSON.parse(categories).forEach(function (category) {
                    categoriesDiv.append(ConvertStringToHTML(`<a class="btn_primary" href='/category/${category}'>${data[category]}</a>`));
                });
            }
        });
}