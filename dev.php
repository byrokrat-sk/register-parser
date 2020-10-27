<?php

error_reporting(-1);

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

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

echo ("Dev.\n");
