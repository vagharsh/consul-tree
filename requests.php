<?php


$method = $_POST['method'];
$url = $_POST['url'];

if (isset($_POST['value'])) {
    $value = $_POST['value'];
}

if (isset($_POST['recurse'])) {
    $recurse = $_POST['recurse'];
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);

if ($method == "PUT") {
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
} elseif ($method == "DELETE") {
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
}

$result=curl_exec($ch);
curl_close($ch);
echo ($result);
