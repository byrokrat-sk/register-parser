<?php

// Global debug function
function dd($val, $json = false) {
    if ($json) {
        die(json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    } else {
        print_r($val); die("\n");
    }
}

# ~

require_once __DIR__.'/vendor/autoload.php';

echo ("Dev.");
