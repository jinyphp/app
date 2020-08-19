<?php

namespace jiny;

function isAPP()
{
    $type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
    if (!$type == 'application/json') {
        return true;
    }
    return false;
}