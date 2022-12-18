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
const removeLike = async (wisId) => {
    data = {
        '_token': $('meta[name=csrf-token]').attr('content'),
        wisdomId: wisId,
    }
    fetch(`/removeLike/${wisId}`, {
        method: 'GET',
    }).then(res => {
        return res.json();
    }).then(async data => {
        showSnackbar("تم إلغاء الإعجاب");
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
    } else {
        for (var i = 0; i < likesIds.length; i++) {
            if (likesIds[i] === parseInt(wisId)) {
                likesIds.splice(i, 1);
                const shareBt = document.getElementById("like-" + wisId);
                if (shareBt) {
                    setLikesStorage();
                    likeBt.classList.remove("fa-solid");
                    likeBt.style.color = "black";
                    removeLike(wisId);
                }
            }
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