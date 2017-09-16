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