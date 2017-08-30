<?php

function putInConsul($url, $value) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    if ($value != false){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
    }
    $result=curl_exec($ch);
    curl_close($ch);
    return ($result);
}

function getFromConsul($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    $data["data"] = $result;
    $data["http_code"] = $info['http_code'];
    return($data);
}

function deleteFromConsul($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $result = curl_exec($ch);
    curl_close($ch);
    return ($result);
}