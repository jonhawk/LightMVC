<?php
// autoload classes based on a 1:1 mapping from namespace to directory structure
spl_autoload_register(function ($classname) {

    $ds = DIRECTORY_SEPARATOR;
    $dir = APP;

    // replace namespace separator with directory separator
    $classname_orig   = str_replace('\\', $ds, $classname);
    $classname_lower  = mb_strtolower($classname_orig, 'UTF-8');

    // get full name of the file containing the required class
    $file       = "{$dir}{$classname_orig}.php";
    $file_lower = "{$dir}{$classname_lower}.php";
    // third party files
    $tp_file       = "{$dir}thirdparty{$ds}{$classname_orig}.php";
    $tp_file_lower = "{$dir}thirdparty{$ds}{$classname_lower}.php";

    // get file if it is readable
    if (is_readable($file)) {
        require_once $file;
    } elseif (is_readable($file_lower)) {
        require_once $file_lower;
    } elseif (is_readable($tp_file)) {
        require_once $tp_file;
    } elseif (is_readable($tp_file_lower)) {
        require_once $tp_file_lower;
    }
});