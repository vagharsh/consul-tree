<?php

$url = $_POST['url'];

if (isset($_POST['value'])) {
    $value = $_POST['value'];
}

$decodedJson = json_decode($value);
foreach ($decodedJson as $item) {
    $keyUrl = $url . $item->key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$keyUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $item->value);
    $result=curl_exec($ch);
    curl_close($ch);
}