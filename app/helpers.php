<?php
function adjustLineBreaks($wisdom, $twitter)
{
    if ($twitter) {
        str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $wisdom);
        str_replace(array("\r\n", "\r", "\n"), "<br />", $wisdom);
        $wisdom = nl2br($wisdom, true);
        $wisdom = str_replace("<br />", "%0A", $wisdom);
        echo $wisdom;
    } else {
        str_replace(['&nbsp;', '/\xc2\xa0/', '\\u00a0"'], " ", $wisdom);
        str_replace(array("\r\n", "\r", "\n"), "<br />", $wisdom);
        str_replace("<br>", "<br />", $wisdom);
        echo nl2br($wisdom, false);
    }
}
