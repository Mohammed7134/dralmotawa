<?php
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
