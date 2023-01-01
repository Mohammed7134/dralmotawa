text_truncate = function (str, length, ending) {
    if (length == null) {
        length = 100;
    }
    if (ending == null) {
        ending = '....المزيد';
    }
    if (str.length > length && window.innerWidth >= 768) {
        str = str.replace(/\n/g, " ");
        return str.substring(0, length - ending.length) + ending;
    } else {
        return str;
    }
};