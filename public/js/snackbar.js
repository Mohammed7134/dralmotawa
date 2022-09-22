function showSnackbar(message, wisId = null, time = 2400) {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Write the message
    x.innerText = message;
    if (wisId != null) {
        let deleteBtn = document.createElement("button");
        deleteBtn.classList.add("btn", "btn-danger");
        deleteBtn.addEventListener("click", async () => { await deleteWisdom(wisId); });
        deleteBtn.innerText = "نعم";
        x.append(deleteBtn);
    }
    // Add the "show" class to DIV
    x.className = "show";

    // After defined seconds, remove the show class from DIV
    setTimeout(function () { x.className = x.className.replace("show", ""); }, time);
}