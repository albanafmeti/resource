<?php

/* Helpers Functions */

if (!function_exists("is_iterable")) {
    function is_iterable($entity) {
        if ($entity instanceof \Traversable || is_array($entity)) {
            return true;
        }
        return false;
    }
}