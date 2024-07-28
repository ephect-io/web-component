<?php
$vendorPos = strpos( __DIR__, 'vendor');
$vendorPath = 'vendor';

if($vendorPos > -1) {
    $vendorPath = substr(__DIR__, 0, $vendorPos) . $vendorPath;
}

include $vendorPath . '/ephect-io/framework/Ephect/bootstrap.php';
include $vendorPath . '/autoload.php';
