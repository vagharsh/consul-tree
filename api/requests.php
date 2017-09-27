<?php

require 'functions.php';

if (isset($_POST['method'])) {
    $method = $_POST['method'];

    if ($method === 'DELETE') {
        if (isset($_POST['consul']) && isset($_POST['path'])) {
            $consul = $_POST['consul'];
            $path = $_POST['path'];

            $pathsList = json_decode($path);
            foreach ($pathsList as $item) {
                $keyUrl = $consul . $item;
                echo(deleteFromConsul($keyUrl));
            }
        }
    } elseif ($method === 'PUT') {
        if (isset($_POST['path']) && isset($_POST['value'])) {
            $value = $_POST['value'];
            $path = $_POST['path'];
            echo(putInConsul($path, $value));
        }
    } elseif ($method === 'BulkIMPORT') {
        if (isset($_POST['path']) && isset($_POST['value'])) {
            $value = $_POST['value'];
            $path = $_POST['path'];
            
            $decodedJson = json_decode($value);
            foreach ($decodedJson as $key => $value) {
                $keyUrl = $path . $key;
                echo(putInConsul($keyUrl, $value));
            }
        }
    } elseif ($method === 'CCP') {
        if (isset($_POST['replace']) && isset($_POST['parentId']) && isset($_POST['path']) && isset($_POST['consul'])) {
            $replaceWith = $_POST['replace'];
            $parentId = $_POST['parentId'];
            $path = $_POST['path'];
            $consul = $_POST['consul'];
            $ccType = $_POST['ccType'];

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
    } elseif ($method === 'EXPORT') {
        if (isset($_POST['consul'])) {
            $consul = $_POST['consul'];

            $toBeExportedData = array();
            if (isset($_POST['path'])) {
                $path = $_POST['path'];
            } else {
                $path = getFromConsul($consul . "?keys")['data'];
            }
    
            $decodedPaths = json_decode($path);
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
    } elseif ($method === 'RENAME') {
        if (isset($_POST['path']) && isset($_POST['consul'])) {
            $consul = $_POST['consul'];
            $path = $_POST['path'];

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
    }
}
if (isset($_GET['path'])) {
    $path = $_GET['path'];
    echo(json_encode(getFromConsul($path)));
}