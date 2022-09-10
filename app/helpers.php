<?php function limitStringLength($str, $limit = 100)
{
    str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $str);
    str_replace(array("\r\n", "\r", "\n"), "<br />", $str);
    if (mb_strlen($str) > $limit) {
        $str = mb_substr($str, 0, $limit) . '...';
    }
    return $str;
}
function adjustLineBreaks($wisdom)
{
    echo nl2br($wisdom, false);
}
