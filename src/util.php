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