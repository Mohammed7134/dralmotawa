<?php
function limitStringLength($str, $limit = 80)
{
    str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $str);
    str_replace(array("\r\n", "\r", "\n"), "<br />", $str);
    str_replace("<br>", "<br />", $str);
    $long = mb_strlen($str) > $limit;
    if ($long) {
        $str = mb_substr($str, 0, $limit) . '...';
    }
    return [$str, $long];
}
function adjustLineBreaks($wisdom, $textarea)
{
    if ($textarea) {
        echo $wisdom;
    } else {
        str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $wisdom);
        str_replace(array("\r\n", "\r", "\n"), "<br />", $wisdom);
        str_replace("<br>", "<br />", $wisdom);
        echo nl2br($wisdom, false);
    }
}
function removeLineBr($text)
{
    echo str_replace("<br>", "\n", $text);
}
