let likesIds = [];
if (myStorage.getItem("likes")) {
    likesIds = JSON.parse(myStorage.getItem("likes"));
}
const setLikesStorage = () => {
    myStorage.setItem("likes", JSON.stringify(likesIds));
}
const sendLike = async (wisId) => {
    data = {
        '_token': $('meta[name=csrf-token]').attr('content'),
        wisdomId: wisId,
    }
    fetch(`/likeWisdom/${wisId}`, {
        method: 'GET',
    }).then(res => {
        return res.json();
    }).then(async data => {
        showSnackbar("تم تسجيل الإعجاب");
    })
}
const addLike = (wisId) => {
    const likeBt = document.getElementById("like-" + wisId);
    if (!likesIds.includes(parseInt(wisId))) {
        if (likeBt) {
            likesIds.push(parseInt(wisId));
            setLikesStorage();
            likeBt.classList.add("fa-solid");
            likeBt.style.color = "red";
            sendLike(wisId);
        }
    }
}
var likeColorCheck = function () {
    for (let i = 0; i < likesIds.length; i++) {
        const el = document.getElementById(`like-${likesIds[i]}`);
        if (el) {
            el.classList.add("fa-solid")
            el.style.color = "red"
        }
    }
}
likeColorCheck();

$(document).click(function (event) {
    const wisId = $(event.target).attr('id');
    if (wisId && wisId.includes("like-")) {
        const cmp = wisId.split("-");
        addLike(cmp[1]);
    }
});