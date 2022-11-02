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
}