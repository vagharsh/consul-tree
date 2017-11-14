<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
if (empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
    header('Location: backend/login.php');
}

$consulTreeVersion = trim(file_get_contents('backend/version'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title id="pageTitle"></title>
    <link rel="stylesheet" href="lib/css/tree.css"/>
    <link rel="stylesheet" href="lib/themes/default/style.min.css"/>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/png" href="lib/favicon.png"/>
    <script src="lib/js/jquery-3.2.1.min.js"></script>
    <script src="lib/js/bootstrap.min.js"></script>
    <script src="lib/js/jstree.js"></script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#" id="consulTitleID"></a>
            </div>
            <form class="navbar-form navbar-right" action="backend/logout.php">
                <div class="form-group">
                    <select class="form-control" id="consulUrlSelectorId" title="Consul-Urls"></select>
                </div>
                <button type="button" class="btn btn-default" aria-label="Left Align" id="resetLocationBtnId"
                        data-toggle="tooltip" data-placement="bottom" title="Reset consul location settings">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
                <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"
                        title="Logged in <?php echo $_SESSION["auto"] ? "automatically" : ""; ?> as <?php echo $_SESSION['username']; ?>">Logout
                </button>
            </form>
            <p class="navbar-text navbar-right">Consul locations</p>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label class="sr-only" for="searchInputId">Search</label>
                <input id="searchInputId" value="" class="form-control search-box" placeholder="Search"
                       style="margin:0 auto 1em auto;  padding:4px;">
                <span id="searchClear" class="glyphicon glyphicon-search"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <button type="button" id="importExportBtnId" class="btn btn-primary writeACL" disabled
                        data-toggle="modal"
                        data-target="#importExportModalId">Import
                </button>
                <button type="button" class="btn btn-warning readACL" disabled id="enableExportBtnId">Enable Export</button>
                <button type="button" class="btn btn-info hidden readACL" disabled id="disableManualExport">Disable Export</button>
                <button type="button" class="btn btn-success hidden readACL" disabled id="exportSelection">Export Selection</button>
                <button type="button" class="btn btn-primary writeACL" disabled id="createRootBtnId">Create Folder / Key</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><div id="ConsulTree" class="well"></div></div>
        <div class="col-md-6" id="generalValueAreaID">
            <div id="keyValueFieldsid" class="panel panel-default">
                <div class="panel-heading">No item is selected to view it's value.</div>
                <div class="panel-body">
                    <span id="createElementText" style="color: #737373" class="readACL">Select a key to get its value
                        <span class="writeACL">, right-click on the tree to create an element</span>.</span>
                    <div class="form-group update-control hidden">
                    <textarea class="form-control update-control hidden" id="cKeyValue" rows="8" readonly
                              title="Value"></textarea>
                    </div>
                    <button type="button" disabled id="valueUpdateBtnId"
                            class="btn btn-primary update-control hidden writeACL">Update
                    </button>
                    <span class="update-control hidden writeACL" style="color: #737373">&nbsp;&nbsp;To create an element, right-click on the tree.</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('backend\modals.html'); ?>
<p class="hidden" id="userRights"></p>
<p class="hidden" id="selectedNodeID"></p>
<p class="hidden" id="gotNodeValue"></p>
<footer id="pageFooter">
    <div class="container">
        <p class="navbar-text navbar-lef">Consul-tree <?php echo $consulTreeVersion; ?></p>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="https://github.com/vagharsh/consul-tree">GitHub Project</a></li>
        </ul>
    </div>
</footer>
<script>
    var userRights = "<?php echo (string)$_SESSION['rights']; ?>";
</script>
<script src="lib/js/functions.js"></script>
<script src="lib/js/triggers.js"></script>
<script src="lib/js/manager.js"></script>
</body>
</html>