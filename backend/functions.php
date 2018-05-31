<?php

function putInConsul($path, $value, $cas) {
    if ($cas == 0){$path = $path."?cas=0";}
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
    $path = $path."?recurse";
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
    $keyUrl = $consul . $path;
    $result = deleteFromConsul($keyUrl);
    return ($result);
}

function importFn($consul, $path, $value, $cas){
    $result = array();
    $keyUrl = $consul . $path;
    $result['key'] =  $path;

    if ($value != NULL){
        $valHash = hash('tiger192,3', $value);
        putInConsul($keyUrl, $value, $cas);
        $getValue = getFromConsul($keyUrl."?raw")['data'];
        $newValHash = hash('tiger192,3', $getValue);
        if ($valHash === $newValHash){
            $result['value'] =  "OK";
        } else {
            $result['value'] =  "NOK";
        }
    } else {
        putInConsul($keyUrl, false, $cas);
    }
    return ($result);
}

function ccpFn($path, $parentId, $replaceWith, $consul, $ccType, $cas, $srcConsul){
    $pathsList = json_decode(getFromConsul($srcConsul. $path . "?keys")['data']);
    foreach ($pathsList as $item) {
        $lastChar = substr($item, -1);
        if ($parentId === ''){
            $newPath = $replaceWith . $item;
        } else {
            $newPath = str_replace($parentId, $replaceWith, $item);
        }
        $newUrl = $consul . $newPath;
        $sourceUrl = $srcConsul . $item;

        if ($lastChar == '/') {
            putInConsul($newUrl, false, 1);
        } else {
            $sourceUrl = $sourceUrl . "?raw";
            putInConsul($newUrl, getFromConsul($sourceUrl)['data'], $cas);
        }
        if ($ccType == 'cut'){
            deleteFromConsul($sourceUrl);
        }
    }
}

function renDupFn($path, $consul, $method){
    $decodedJson = json_decode($path);
    foreach ($decodedJson as $key => $value) {
        $lastChar = substr($key, -1);
        $origUrl = $consul . $key;
        $newUrl = $consul . $value;

        if ($lastChar == '/') {
            putInConsul($newUrl, false, 1);
        } else {
            $sourceUrl = $origUrl . "?raw";
            putInConsul($newUrl, getFromConsul($sourceUrl)['data'], 1);
        }
    }
    if ($method === 'RENAME') {
        deleteFromConsul($consul . $_POST['selectedObj']);
    }
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

    $filename = sys_get_temp_dir() . '/consul-tree.json';
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

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir")
                    rrmdir($dir."/".$object);
                else unlink   ($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function fixTreeFn ($path, $consul){
    $manage = json_decode($path);
    $consulUrlModified = '';

    ini_set('max_execution_time', 120);

    $directory = sys_get_temp_dir() . '/consul/';

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
            putInConsul($name, false, 1);
        }
    }

    $directory = sys_get_temp_dir() . '/consul';
    rrmdir($directory);
}

function redirectTo(){
    $BASE_URI = getenv("BASE_URI");

    if ($BASE_URI){
        header('location: /' . $BASE_URI );
        exit();
    } else {
        header('Location: /' );
        exit();
    }
}

function importFnAPI($consul, $filePath){
    $fileContents = json_decode(file_get_contents($filePath));
    $result = array();

    foreach ($fileContents as $key => $value) {
        $keyUrl = $consul . $key;

        if ($value != NULL){
            $valHash = hash('tiger192,3', $value);
            putInConsul($keyUrl, $value, 1);
            $getValue = getFromConsul($keyUrl."?raw")['data'];
            $newValHash = hash('tiger192,3', $getValue);
            if ($valHash === $newValHash){
                $result[$key] =  "OK";
            } else {
                $result[$key] =  "NOK";
            }
        } else {
            putInConsul($keyUrl, false, 1);
        }
    }
    return ($result);
}
