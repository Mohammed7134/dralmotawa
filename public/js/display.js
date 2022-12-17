function buttonPressed(e) {
    if (window.innerWidth >= 768) {
        const blogPostContainer = document.querySelectorAll("#current_wisdom");
        for (post of blogPostContainer) {
            post.style.display = "none";
        }
        const wisdomId = e.id.split("-")[1];
        const displayedWisdom = document.querySelector(`.displayed_wisdom_${wisdomId}`);
        displayedWisdom.style.display = "";
        displayedWisdom.scrollIntoView();
    }
}