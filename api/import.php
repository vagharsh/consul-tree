<?php

$list = $_POST['urls'];
$consulUrl = $_POST['consulUrl'];

$manage = json_decode($list);
$directory = './tmp/consul/';

foreach ($manage as $item){
    $path = $directory . $item;
    $lastChar = substr($path, -1);

    if ($lastChar == '/'){
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    } else {
        mkdir($directory . dirname($item) . '/', 0777, true);
    }
}

function putInConsul($post, $value) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$post);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    if ($value != false){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
    }
    $result=curl_exec($ch);
    curl_close($ch);
    return ($result);
}

if(substr($consulUrl, -1) == '/') {
    $consulUrlModified = substr($consulUrl, 0, -1);
}

$scanned_directory = array_diff(scandir($directory), array('..', '.', 'undefined'));

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);
foreach($objects as $name => $object){
    $lastChar = substr($name, -1);
    if ($lastChar != '.' && $lastChar != '..'  && $lastChar != '\\' ){
        $name = str_replace("./tmp/consul","",$name);
        $name = str_replace("\\","/",$name);
        $name = $consulUrlModified . $name .'/';
        putInConsul($name, false);
    }
}

if (PHP_OS === 'Windows'){
    exec(sprintf("rd /s /q %s", escapeshellarg($directory)));
} else {
    exec(sprintf("rm -rf %s", escapeshellarg($directory)));
}