<?php

function putInConsul($path, $value) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$path);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    if ($value != false){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
    }
    $result=curl_exec($ch);
    curl_close($ch);
    return ($result);
}

function getFromConsul($path) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $path);
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    $data["data"] = $result;
    $data["http_code"] = $info['http_code'];
    return($data);
}

function deleteFromConsul($path){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $path);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $result = curl_exec($ch);
    curl_close($ch);
    return ($result);
}

function deleteFn($path, $consul){
    $pathsList = json_decode($path);
    foreach ($pathsList as $item) {
        $keyUrl = $consul . $item;
        $result = deleteFromConsul($keyUrl);
    }
    return ($result);
}

function importFn($path, $value){
    $pathsList = json_decode($value);
    foreach ($pathsList as $key => $value) {
        $keyUrl = $path . $key;
        $result = putInConsul($keyUrl, $value);
    }
    return ($result);
}

function ccpFn($path, $parentId, $replaceWith, $consul, $ccType){
    $pathsList = json_decode($path);
    foreach ($pathsList as $item) {
        $lastChar = substr($item, -1);
        $newPath = str_replace($parentId, $replaceWith, $item);
        $newUrl = $consul . $newPath;
        $sourceUrl = $_POST['srcConsul'] . $item;

        if ($lastChar == '/') {
            putInConsul($newUrl, false);
        } else {
            $sourceUrl = $sourceUrl . "?raw";
            putInConsul($newUrl, getFromConsul($sourceUrl)['data']);
        }
        if ($ccType == 'cut'){
            deleteFromConsul($sourceUrl);
        }
    }
}

function renameFn($path, $consul){
    $decodedJson = json_decode($path);
    foreach ($decodedJson as $key => $value) {
        $lastChar = substr($key, -1);
        $origUrl = $consul . $key;
        $newUrl = $consul . $value;

        if ($lastChar == '/') {
            putInConsul($newUrl, false);
        } else {
            $sourceUrl = $origUrl . "?raw";
            putInConsul($newUrl, getFromConsul($sourceUrl)['data']);
        }

        deleteFromConsul($origUrl);
    }
    deleteFromConsul($consul . $_POST['selectedObj']);
}

function exportFn($path, $consul){
    $decodedPaths = json_decode($path);
    $toBeExportedData = array();

    foreach ($decodedPaths as $item) {
        $lastChar = substr($item, -1);
        if ($lastChar == '/') {
            $toBeExportedData[$item] = null;
        } else {
            $sourceUrl = $consul . $item . "?raw";
            $toBeExportedData[$item] = getFromConsul($sourceUrl)['data'];
        }
    }

    $filename = sys_get_temp_dir() . 'consul-tree.json';
    $fp = fopen($filename, 'w');
    fwrite($fp, json_encode($toBeExportedData));
    fclose($fp);

    if (file_exists($filename)) {
        header("Content-disposition: attachment; filename=" . $filename);
        header("Content-type: application/json");
        header('Pragma: no-cache');
        readfile($filename);
        unlink($filename);
    }
}

function fixTreeFn ($path, $consul){
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

    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);

    foreach ($objects as $name => $object) {
        $lastChar = substr($name, -1);
        if ($lastChar != '.' && $lastChar != '..' && $lastChar != '\\') {
            $directory = str_replace('\\', "/", $directory);
            $name = str_replace('\\', "/", $name);
            $name = str_replace($directory, "", $name);
            $name = $consulUrlModified . "/" .$name . '/';
            putInConsul($name, false);
        }
    }

    if (PHP_OS === 'Windows') {
        exec(sprintf("rd /s /q %s", escapeshellarg($directory)));
    } else {
        exec(sprintf("rm -rf %s", escapeshellarg($directory)));
    }
}