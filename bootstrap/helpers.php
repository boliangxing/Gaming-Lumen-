<?php

define('ASSET_SERVER',  env('ASSET_SERVER'));

function avatar($partial, $style = '') {
    if ($style) {
        return ASSET_SERVER . '/' . $partial . '@!' . $style;
    }

    return ASSET_SERVER . '/' . $partial;
}

function asset($partial, $style = '') {
    if ($style) {
        return ASSET_SERVER . '/' . $partial . '@!' . $style;
    }

    return ASSET_SERVER . '/' . $partial;
}