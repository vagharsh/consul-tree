<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$consulTreeVersion = trim(file_get_contents('version'));

$password_err = null;
$username_err = null;
$rights_err = null;

$BASE_URI = getenv("BASE_URI");
if ($BASE_URI){
    $appRoot = '/'. $BASE_URI . '/backend/';
} else {
    $appRoot = $_SERVER["PHP_SELF"];
}

require_once('functions.php');
require_once("ACL.php");

$acl = new ACL;
$mainTitle = $acl->mainTitle;

if ($acl->autoCheck){
    session_start();
    $_SESSION["username"] = $acl->username;
    $_SESSION["authenticated"] = 'true';
    $_SESSION["rights"] = $acl->getRights();
    $_SESSION["auto"] = $acl->autoCheck;
    redirectTo();
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if username is empty
        if (empty(trim($_POST["username"]))) {
            $username_err = 'Please enter your username.';
        } else {
            $username = trim($_POST["username"]);
        }

        // Check if password is empty
        if (empty(trim($_POST['password']))) {
            $password_err = 'Please enter your password.';
        } else {
            $password = trim($_POST['password']);
        }

        if(!empty($_POST["username"]) && !empty($_POST["password"])) {
            $username = trim($_POST["username"]);
            $password = trim($_POST['password']);

            if ($acl->checkUser($username)) {
                if ($acl->checkPass($password)) {
                    if ($acl->getRights() != 000) {
                        session_start();
                        $_SESSION["username"] = $username;
                        $_SESSION["authenticated"] = 'true';
                        $_SESSION["rights"] = $acl->getRights();
                        $_SESSION["auto"] = $acl->autoCheck;
                        redirectTo();
                    } else {
                        $rights_err = "You are not Authorized to perform this action";
                    }
                } else {
                    $password_err = 'The password you entered was not valid.';
                }
            } else {
                $username_err = 'No account found with that username.';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title id="pageTitle"></title>
    <link href="../lib/css/login.css" rel="stylesheet" >
    <link href="../lib/favicon.png" rel="shortcut icon" type="image/png" />
    <link href="../lib/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="login-page">
    <div class="form">
        <form class="login-form" action="<?php echo htmlspecialchars($appRoot); ?>" method="post">
            <div class="page-header">
                <h3><?php echo $mainTitle; ?></h3>
            </div>
            <div class="form-group">
                <input name="username" placeholder="username"/>
                <span class="text-danger"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="password"/>
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <span class="text-danger"><?php echo $rights_err; ?></span>
            </div>
            <button>Login</button>
        </form>
        <br>
        <h6 class="text-center" style="">Consul-tree <?php echo $consulTreeVersion; ?></h6>
    </div>
</div>
<script src="../lib/js/jquery-3.7.0.min.js"></script>
<script src="../lib/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#pageTitle').text("Consul Tree | " + window.location.hostname);
    })
</script
</body>
</html>
