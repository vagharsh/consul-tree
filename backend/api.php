<?php

require_once('functions.php');

$okAUTH = "Basic GbYpjCigzBM4PayeozwG";

if (isset($_POST['method']) && $_POST['method'] === "IMPORT") {
    $auth = NULL;

    if (array_key_exists('Authorization', getallheaders())) {
        $auth = getallheaders()['Authorization'];
    } else {
        header("HTTP/1.1 401 Unauthorized, No Authorization was provided");
        exit;
    }

    if ($auth !== $okAUTH) {
        header("HTTP/1.1 401 Unauthorized, Invalid Token");
        exit;
    } else {
        $filePath = sys_get_temp_dir() . "/" . sha1_file($_FILES['file']['tmp_name']) . ".tmp";
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

        $importedData = importFnAPI($_POST['consul'], $filePath);

        foreach ($importedData as $key => $value) {
            echo $value . " --> " . $key . PHP_EOL;
        }
        unlink($filePath);
    }
}