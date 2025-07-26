<?php

function get_options($length = 10, $with_header = false)
{
    if ($with_header)
        $options = ['' => 'Please select'];
    foreach (range(1, $length) as $number) {
        $options['Option ' . $number] = 'Option ' . $number;
    }
    return $options;
}
