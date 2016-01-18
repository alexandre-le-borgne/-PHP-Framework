<?php

function date_to_time($date, $format = "Y-m-d H:i:s") {
    if(!$date)
        return;
    return @date_format(date_create_from_format ($format, $date), 'U');
}