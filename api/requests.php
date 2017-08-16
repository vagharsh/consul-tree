<?php

$method = $_POST['method'];
$url = $_POST['url'];

require 'functions.php';

if (isset($_POST['consulUrl'])) {
    $consulUrl = $_POST['consulUrl'];
}

if (isset($_POST['value'])) {
    $value = $_POST['value'];
}

if (isset($_POST['parentId'])) {
    $parentId = $_POST['parentId'];
}

if (isset($_POST['replace'])) {
    $replaceWith = $_POST['replace'];
}

if ($method === 'DELETE') {
    $decodedJson = json_decode($url);
    if (!is_array($decodedJson)) {$decodedJson = [$decodedJson];}
    foreach ($decodedJson as $item) {
        $keyUrl = $consulUrl . $item;
        echo (deleteFromConsul($keyUrl));
    }
} elseif ($method === 'PUT') {
    echo (putInConsul($url, $value));
} elseif ($method === 'BulkIMPORT') {
    $decodedJson = json_decode($value);
    foreach ($decodedJson as $item) {
        $keyUrl = $url . $item->key;
        echo (putInConsul($keyUrl, $item->value));
    }
} elseif ($method === 'CCP') {
    $decodedJson = json_decode($url);
    foreach ($decodedJson as $item) {
        $lastChar = substr($item, -1);
        $newPath = str_replace($parentId,$replaceWith,$item);
        $fullUrl = $consulUrl . $newPath;
        $sourceUrl = $consulUrl . $item;

        if ($lastChar == '/'){
            putInConsul($newPath, false);
        } else {
            $sourceUrl = $sourceUrl . "?raw";
            putInConsul($fullUrl, getFromConsul($sourceUrl));
        }
    }
} else {
    echo (getFromConsul($url));
}
