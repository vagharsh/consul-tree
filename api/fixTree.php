<?php

require 'functions.php';

if (isset($_POST['consul'])) {
    $consul = $_POST['consul'];

    if (isset($_POST['path'])) {
        $path = $_POST['path'];
    } else {
        $path = getFromConsul($consul . "?keys")['data'];
    }

    $manage = json_decode($path);

    $directory = sys_get_temp_dir() . '/consul/';

    ini_set('max_execution_time', 120);

    if (PHP_OS === 'Windows') {
        exec(sprintf("rd /s /q %s", escapeshellarg($directory)));
    } else {
        exec(sprintf("rm -rf %s", escapeshellarg($directory)));
    }

    foreach ($manage as $item) {
        $path = $directory . $item;
        $lastChar = substr($path, -1);

        if ($lastChar == '/') {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        } else {
            mkdir($directory . dirname($item) . '/', 0777, true);
        }
    }

    if (substr($consul, -1) == '/') {
        $consulUrlModified = substr($consul, 0, -1);
    }

    $scanned_directory = array_diff(scandir($directory), array('..', '.', 'undefined'));

    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($objects as $name => $object) {
        $lastChar = substr($name, -1);
        if ($lastChar != '.' && $lastChar != '..' && $lastChar != '\\') {
            $name = str_replace("./tmp/consul", "", $name);
            $name = str_replace("\\", "/", $name);
            $name = $consulUrlModified . $name . '/';
            putInConsul($name, false);
        }
    }

    if (PHP_OS === 'Windows') {
        exec(sprintf("rd /s /q %s", escapeshellarg($directory)));
    } else {
        exec(sprintf("rm -rf %s", escapeshellarg($directory)));
    }
}