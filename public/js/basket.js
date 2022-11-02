var support = (function () {
    if (!window.DOMParser) return false;
    var parser = new DOMParser();
    try {
        parser.parseFromString('x', 'text/html');
    } catch (err) {
        return false;
    }
    return true;
})();

var textToHTML = function (str) {

    // check for DOMParser support
    if (support) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(str, 'text/html');
        return doc.body.innerHTML;
    }

    // Otherwise, create div and append HTML
    var dom = document.createElement('div');
    dom.innerHTML = str;
    return dom;

};
let wisdomsIds = [];
var checkColor = function () {
    for (let i = 0; i < wisdomsIds.length; i++) {
        const el = document.getElementById(`add-${wisdomsIds[i]}`);
        el ? el.style.color = "gray" : null
    }
}

//This is to remove the non-breaking-space from the wisdoms
String.prototype.removeNBSP = function () {
    return this.replace(/&nbsp;/g, ' ').replace(/&#160;/g, ' ').replace(/\u00a0/g, ' ').replace("</ br>", "");
};
const author = " "; //"د.&nbsp;عبدالعزيز&nbsp;فيصل&nbsp;المطوع";
const authorWhats = "د. عبدالعزيز فيصل المطوع";
const successText = "تم التعديل بنجاح";
const successDeleteText = "تم الحذف بنجاح";
const successAddText = "تمت الإضافة بنجاح"
const errorText = "حدث خطأ";
const noChangeText = "لم يتغير شيء";
const noWisdoms = "لا يوجد حكم بالسلة";
const longPressRequired = "اضغط مطولا لمسح الحكم بالسلة";
const baseUrl = "/v1/php/"
const myStorage = window.sessionStorage;
const spinnerTag = '<wisdomFoote class="spinner-border" role="status"></wisdomFoote>';
let logStatus = false;
let wisdomsText = [];
let numberOfMessages = document.querySelector(".number-of-messages");
let allText = "";
function deleteWisdom(wisId) {
    window.location = `/delete/${wisId}`;
}
//Snackbar function
async function showSnackbar(message, wisId = null, time = 2400) {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Write the message
    x.innerText = message;
    if (wisId != null) {
        let deleteBtn = document.createElement("button");
        deleteBtn.classList.add("btn", "btn-danger");
        deleteBtn.addEventListener("click", async () => { deleteWisdom(wisId); });
        deleteBtn.innerText = "نعم";
        x.append(deleteBtn);
    }
    // Add the "show" class to DIV
    x.className = "show";

    // After defined seconds, remove the show class from DIV
    setTimeout(function () { x.className = x.className.replace("show", ""); }, time);
}
const shareButton = document.querySelector(".share-link");
const shareButtonTag = '<i class="fab fa-whatsapp fa-2x" style="color:black" aria-hidden="true"></i>';

const formElem = document.querySelector("#editForm");
const deleteElem = document.querySelector("#deleteForm");
const textarea = document.querySelector("textarea");
const innerContent = document.querySelector(".inner-content");
const btnScrollToTopId = document.querySelector("#btnScrollToTopId");
const menu = document.querySelector("#categories");
// if (myStorage.getItem("wisdoms")) {
//     wisdomsIds = JSON.parse(myStorage.getItem("wisdoms"));
//     if (wisdomsIds.length > 0) {
//         shareButton.innerHTML = spinnerTag;
//         let data = {
//             '_token': $('meta[name=csrf-token]').attr('content'),
//             wisdomsIds: wisdomsIds
//         }
//         fetch('/getWisdomById', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json', },
//             body: JSON.stringify(data),
//         }).then(res => {
//             return res.json();
//         }).then(async data => {
//             if (data.error === false) {
//                 wisdomsText = data.wisdoms;
//             } else {
//                 await showSnackbar(errorText);
//             }
//         }).then(() => {
//             shareButton.innerHTML = shareButtonTag;
//             numberOfMessages.innerText = wisdomsIds.length;
//             numberOfMessages.style.display = "flex";
//             setNumberVisibility();
//         })
//     }
// }
checkColor();
shareButton.style["-webkit-user-select"] = "none";
numberOfMessages.style["-webkit-user-select"] = "none";

const setWisdomsStorage = () => {
    myStorage.setItem("wisdoms", JSON.stringify(wisdomsIds));
}

$(document).click(function (event) {
    const wisId = $(event.target).attr('id');
    if (wisId && wisId.includes("add-")) {
        const cmp = wisId.split("-");
        addRemoveWisdomTest(cmp[1]);
    }
});
const addRemoveWisdomTest = async (wisId, remove = false) => {
    const shareBt = document.getElementById("add-" + wisId);
    const wisText = document.getElementById(wisId);
    if (wisdomsIds.includes(parseInt(wisId))) {
        for (var i = 0; i < wisdomsIds.length; i++) {
            if (wisdomsIds[i] === parseInt(wisId)) {
                wisdomsIds.splice(i, 1);
                wisdomsText.splice(i, 1);
                const shareBt = document.getElementById("add-" + wisId);
                if (shareBt) {
                    shareBt.style.color = shareBt.classList[4];
                }
            }
        }
    } else {
        if (shareBt && !remove) {
            wisdomsIds.push(parseInt(wisId));
            wisdomsText.push(wisText.innerText);
            shareBt.style.color = "gray";
        }
    }

    setWisdomsStorage();
    await setNumberVisibility();
}


//This function is to hide numberOfMessages and disable WhatsApp link
const disableWhatsAppIcon = () => {
    numberOfMessages.style.display = "none";
    shareButton.removeAttribute("href");
}

//This function set the WhatsApp Icon on the bottom right corner of the screen
//Everytime the page loads it is set to either zero or the number of wisdoms collected by the user
//It also prepares the text to be sent once the icon is tapped
const setNumberVisibility = async () => {
    if (wisdomsIds.length === 0) {
        disableWhatsAppIcon();
    } else {
        shareButton.innerHTML = spinnerTag;
        numberOfMessages.innerText = wisdomsIds.length;
        numberOfMessages.style.display = "flex";
        shareButton.innerHTML = shareButtonTag;
        shareButton.setAttribute("href", `whatsapp://send?text=${encodeURI(wisdomsText.join("\n ___________ \n") + '\n\n' + authorWhats)}`);
    }
}
setNumberVisibility();


//Making the wisdomsIds array empty and reflecting this in the interface;
let pressTimer;
const emptyingWisdomsIds = async function () {
    wisdomsIds = [];
    wisdomsText = [];
    setWisdomsStorage();
    disableWhatsAppIcon();
    shareButton.style.transform = "scale(1.0)";
    let addButtons = document.querySelectorAll(".share-icon");
    for (let i = 0; i < addButtons.length; i++) {
        addButtons[i].style.color = addButtons[i].classList[4];
    }
}

//This function is to call emptyingWisdomsIds array by the user by touching the WhatsApp icon for 1 sec
shareButton.addEventListener('touchstart', function () {
    shareButton.style.transform = "scale(1.5)";
    pressTimer = setTimeout(emptyingWisdomsIds, 1000);
    return false;
});
shareButton.addEventListener('touchend', function () {
    shareButton.style.transform = "scale(1.0)";
    clearTimeout(pressTimer);
    return false;
});