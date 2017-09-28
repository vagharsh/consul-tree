<?php

require 'functions.php';

if (isset($_POST['method'])) {
    $method = $_POST['method'];

    if ($method === 'DELETE') {
        if (isset($_POST['consul']) && isset($_POST['path'])) {
            echo(deleteFn($_POST['path'], $_POST['consul']));
        }
    } elseif ($method === 'PUT') {
        if (isset($_POST['path']) && isset($_POST['value'])) {
            echo(putInConsul($_POST['path'], $_POST['value']));
        }
    } elseif ($method === 'BulkIMPORT') {
        if (isset($_POST['path']) && isset($_POST['value'])) {
            echo (importFn($_POST['path'], $_POST['value']));
        }
    } elseif ($method === 'CCP') {
        if (isset($_POST['replace']) && isset($_POST['parentId']) && isset($_POST['path']) && isset($_POST['consul'])) {
            ccpFn($_POST['path'], $_POST['parentId'], $_POST['replace'], $_POST['consul'], $_POST['ccType']);
        }
    } elseif ($method === 'EXPORT') {
        if (isset($_POST['consul'])) {
            $consul = $_POST['consul'];
            $path = isset($_POST['path']) ? $_POST['path'] : getFromConsul($consul . "?keys")['data'];
            exportFn($path, $consul);
        }
    } elseif ($method === 'RENAME') {
        if (isset($_POST['path']) && isset($_POST['consul'])) {
            renameFn($_POST['path'], $_POST['consul']);
        }
    } elseif ($method === 'FIX') {
        if (isset($_POST['path']) && isset($_POST['consul'])) {
            $consul = $_POST['consul'];
            $path = isset($_POST['path']) ? $_POST['path'] : getFromConsul($consul . "?keys")['data'];
            fixTreeFn($path, $_POST['consul']);
        }
    }
}
if (isset($_GET['path'])) {
    echo(json_encode(getFromConsul($_GET['path'])));
}