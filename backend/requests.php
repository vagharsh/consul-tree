<?php
session_start();
if(empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
    redirectTo();
}

$userRights = (string)$_SESSION['rights'];
require_once('functions.php');

if (isset($_POST['method'])) {
    $method = $_POST['method'];

    if ($method === 'DELETE') {
        if ($userRights[2] == 1) {
            if (isset($_POST['consul']) && isset($_POST['path'])) {
                echo(deleteFn($_POST['path'], $_POST['consul']));
            }
        } else {
            echo "You are not Authorized to perform the DELETE action";
        }
    } elseif ($method === 'PUT') {
        if ($userRights[1] == 1) {
            $cas = isset($_POST['cas']) ? $_POST['cas'] : 0;
            if (isset($_POST['path']) && isset($_POST['value'])) {
                echo(putInConsul($_POST['path'], base64_decode($_POST['value']), $cas));
            }
        } else {
            echo "You are not Authorized to perform the PUT action";
        }
    } elseif ($method === 'IMPORT') {
        if ($userRights[1] == 1) {
            if (isset($_POST['path']) && isset($_POST['value'])) {
                echo (json_encode(importFn($_POST['consul'], $_POST['path'], base64_decode($_POST['value']), 1)));
            }
        } else {
            echo "You are not Authorized to perform the IMPORT action";
        }
    } elseif ($method === 'CCP') {
        if ($userRights[1] == 1) {
            if (isset($_POST['replace']) && isset($_POST['parentId']) && isset($_POST['path']) && isset($_POST['consul']) && isset($_POST['cas']) && isset($_POST['srcConsul'])) {
                ccpFn($_POST['path'], $_POST['parentId'], $_POST['replace'], $_POST['consul'], $_POST['ccType'], $_POST['cas'], $_POST['srcConsul']);
            }
        } else {
            echo "You are not Authorized to perform the CCP action";
        }
    } elseif ($method === 'EXPORT') {
        if ($userRights[0] == 1) {
            if (isset($_POST['path']) && isset($_POST['consul'])) {
                $consul = $_POST['consul'];
                $path = isset($_POST['path']) ? $_POST['path'] : getFromConsul($consul . "?keys")['data'];
                exportFn($path, $consul);
            }
        } else {
            echo "You are not Authorized to perform the EXPORT action";
        }
    } elseif ($method === 'RENAME' || $method === 'DUPLICATE') {
        if ($userRights[1] == 1) {
            if (isset($_POST['path']) && isset($_POST['consul'])) {
                renDupFn($_POST['path'], $_POST['consul'], $method);
            }
        } else {
            echo "You are not Authorized to perform neither RENAME nor DUPLICATE actions";
        }
    } elseif ($method === 'FIX') {
        if ($userRights[0] == 1) {
            if (isset($_POST['path']) && isset($_POST['consul'])) {
                $consul = $_POST['consul'];
                $path = isset($_POST['path']) ? $_POST['path'] : getFromConsul($consul . "?keys")['data'];
                fixTreeFn($path, $_POST['consul']);
            }
        } else {
            echo "You are not Authorized to perform the FIX action";
        }
    }
}

if (isset($_GET['method']) && isset($_GET['consul'])) {
    $method = $_GET['method'];
    if ($method === 'TREE') {
        if ($userRights[0] == 1) {
            echo(json_encode(getFromConsul($_GET['consul'])));
        } else {
            echo "You are not Authorized to perform the GET consul DATA action";
        }
    } elseif ($method === 'VALUE') {
        if ($userRights[0] == 1) {
            echo(json_encode(getFromConsul($_GET['consul'], true)));
        } else {
            echo "You are not Authorized to perform the GET consul DATA action";
        }
    }
}