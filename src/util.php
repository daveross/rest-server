<?php

namespace DaveRoss\RestServer;

/**
 * @return string
 */
function array_first(array $arr) {
    return reset($arr);
}

/**
 * @return string 
 */
function array_last(array $arr) {
    return end($arr);
}

function formatOutput($output) {
    
        if($output instanceof RestResponse) {
            return 'HTTP/1.0 ' . $output->statusCode . ' ' . $output->statusDescription . "\n\n" .
                   $output->payload;
        }
        else if(!is_scalar($output)) {
            return json_encode($output);
        }
        else {
            return $output;
        }

}