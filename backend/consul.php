<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
if (empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
    header('Location: login.php');
}
$userRights = (string)$_SESSION['rights'];
$calledUrl = "$_SERVER[REQUEST_URI]";

if (strpos($calledUrl, 'backend') == false) {
    $calledLoc = '';
    $backendStatus = 'backend/';
} else {
    $calledLoc = '../';
    $backendStatus = '';
}

$autoText = $_SESSION["auto"] ? "automatically" : "";;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title id="pageTitle"></title>
    <link rel="stylesheet" href="<?php echo $calledLoc; ?>lib/css/tree.css"/>
    <link rel="stylesheet" href="<?php echo $calledLoc; ?>lib/themes/default/style.min.css"/>
    <link rel="stylesheet" href="<?php echo $calledLoc; ?>lib/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/png" href="<?php echo $calledLoc; ?>lib/_favicon.png"/>
    <script src="<?php echo $calledLoc; ?>lib/js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo $calledLoc; ?>lib/js/bootstrap.min.js"></script>
    <script src="<?php echo $calledLoc; ?>lib/js/jstree.js"></script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#" id="consulTitleID"></a>
        </div>
        <form class="navbar-form navbar-right" action="<?php echo $backendStatus; ?>logout.php">
            <div class="form-group">
                <select class="form-control" id="consulUrlSelectorId" title="Consul-Urls"></select>
            </div>
            <button type="button" class="btn btn-default" aria-label="Left Align" id="resetLocationBtnId"
                    data-toggle="tooltip" data-placement="bottom" title="Reset consul location settings">
                <span class="glyphicon glyphicon-refresh"></span>
            </button>
            <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom"
                    title="Logged in <?php echo $autoText; ?> as <?php echo $_SESSION['username']; ?>">Logout
            </button>
        </form>
        <p class="navbar-text navbar-right">Consul locations</p>

    </div>
</nav>
<div class="container">
    <div class="page-header">
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-5 padded-right-middle" style="width:505px">
                    <label class="sr-only" for="searchInputId">Search</label>
                    <input id="searchInputId" value="" class="form-control search-box" placeholder="Search"
                           style="margin:0 auto 1em auto;  padding:4px;">
                    <span id="searchClear" class="glyphicon glyphicon-search"></span>
                </div>
                <button type="button" id="importExportBtnId" class="btn btn-primary writeACL" disabled
                        data-toggle="modal"
                        data-target="#importExportModalId">Import
                </button>
                <button type="button" class="btn btn-warning readACL" disabled id="enableExportBtnId">Enable Export
                </button>
                <button type="button" class="btn btn-info hidden readACL" disabled id="disableManualExport">Disable
                    Export
                </button>
                <button type="button" class="btn btn-success hidden readACL" disabled id="exportSelection">Export
                    Selection
                </button>
                <button type="button" class="btn btn-primary writeACL" disabled id="createRootBtnId">Create Folder /
                    Key
                </button>
            </div>
        </form>
    </div>
    <div id="ConsulTree" class="well col-md-5"></div>
    <div class="border-left" id="generalValueAreaID" style="position: fixed; width: 568px; padding-left: 15px">
        <div id="keyValueFieldsid" class="panel panel-default">
            <div class="panel-heading" style="padding: 5px 15px !important;"><h5></h5></div>
            <div class="panel-body">
                <span id="createElementText" style="color: #737373" class="readACL">Select a key to get its value<span
                            class="writeACL">, right-click on the tree to create an element</span>.</span>
                <div class="form-group update-control">
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
<div class="modal fade" id="createNodeModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                </button>
                <h4 class="modal-title"><strong>Create Folder / Key</strong></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <h5 class="control-label">Folder / Key Name : <i>To create a folder, end the
                                key with /</i></h5>
                        <input class="form-control" id="keyInputId" value="" title="Folder / Key Name">
                        <input type="hidden" class="form-control" id="pathInputId" value="">
                    </div>

                    <div class="form-group">
                        <h5 class="control-label">Path : </h5>
                        <textarea class="form-control" id="pathDescribeID" readonly title="Path"></textarea>
                    </div>
                </form>
                <h5 class="control-label inputKeyValueClass">Value : </h5>
                <textarea class="form-control inputKeyValueClass" id="inputKeyValueId" title="Value"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="createKeyBtnId" class="btn btn-info">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="importExportModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                </button>
                <h4 class="modal-title"><strong>Import Consul Data</strong></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Browse JSON file : </label>
                        <input type="file" id="jsonInputFile">
                    </div>
                    <button type="button" id="importConsulBtnId" class="btn btn-info">Import</button>
                    <span style="color: #737373">&nbsp;|&nbsp;Only applicable if the JSON file was exported from the Consul-tree.</span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="noTreeModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-header-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                </button>
                <h4 class="modal-title"><strong>No Data</strong></h4>
            </div>
            <div class="modal-body text-center">
                <span><strong>No Data</strong> was found on Consul, either <strong>Create a folder at the root position</strong>, or <strong>Import</strong> an existing Tree form a previous export</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="noConnectionModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                </button>
                <h4 class="modal-title"><strong>No Connection</strong></h4>
            </div>
            <div class="modal-body text-center">
                <span>Check the connection between the <strong>Consul-Tree</strong> and <strong
                            id="consulUrlId"></strong> and then try again.</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="renameModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                </button>
                <h4 class="modal-title"><strong>Rename Node</strong></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h5 class="control-label">Current node name: </h5>
                    <input id="oldNodePathId" value="" class="form-control" title="Current node name" readonly>
                </div>
                <div class="form-group">
                    <h5 class="control-label">New node name: </h5>
                    <input id="newNodePathId" value="" title="New node name" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="renameConfirmBtnId" data-type="rename" disabled class="btn btn-info">Rename
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="overwriteModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                </button>
                <h4 class="modal-title"><strong>Overwrite existing key values</strong></h4>
            </div>
            <div class="modal-body">
                <span>
                    Do you want to <strong>Overwrite</strong> the existing key values ?
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" data-answer=1 class="btn btn-success overwriteBtn">Yes</button>
                <button type="button" data-answer=0 class="btn btn-danger overwriteBtn">No</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="connectingModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p class="text-center">Establishing connection with <strong id="consulFullUrlId"></strong></p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="processingMdlID" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header modal-header-primary" style="border-bottom: 0">
                <h5 class="modal-title text-center">Processing your request, please wait...</h5>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                         aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="loadingTreeMdlID" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 0">
                <h5 class="modal-title text-center">Validating Tree structure, please wait...</h5>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                         aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<p class="hidden" id="treeAsString"></p>
<p class="hidden" id="ajaxReturnFieldID"></p>
<p class="hidden" id="ajaxReturnVFieldID"></p>
<p class="hidden" id="ccTypeFieldID"></p>
<p class="hidden" id="ccParentFieldID"></p>
<p class="hidden" id="selectedNodeID"></p>
<p class="hidden" id="gotNodeValue"></p>
<a id="downloadAnchorElem" style="display:none"></a>
<footer id="pageFooter">
    <div class="container">
        <p class="navbar-text navbar-lef">Consul-tree v6.7</p>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="https://github.com/vagharsh/consul-tree">GitHub Project</a></li>
        </ul>
    </div>
</footer>
<script src="<?php echo $calledLoc; ?>lib/js/consul-tree.js"></script>
<script src="<?php echo $calledLoc; ?>lib/js/triggers.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('#pageTitle').text("Consul Tree | " + window.location.hostname);
        $.getJSON("<?php echo $calledLoc; ?>config/config.json", function (consul) {
            if (consul) if (consul.length !== 0) {
                var consulUrl, consulTitle, selectedConsulJson, to = false, tree, allKeys,
                    consulUrlSelector = $("#consulUrlSelectorId"),
                    userRights = "<?php echo $userRights; ?>",
                    backendStatus = "<?php echo $backendStatus; ?>",
                    consulTreeDivID = $('#ConsulTree'),
                    leftPos = consulTreeDivID.outerWidth() + consulTreeDivID.offset().left,
                    backendPath = returnBackend(backendStatus);

                getConsulLocations(consul);
                getSetConfig(consul);
                checkRights(userRights);

                selectedConsulJson = JSON.parse(localStorage['selectedConsul']);
                consulUrl = selectedConsulJson.url;
                consulTitle = selectedConsulJson.title;
                consulUrlSelector.val(consulUrl).attr("selected", "selected");
                $('#consulTitleID').text(consulTitle);

                tree = {
                    'contextmenu': {'items': customMenu(false, userRights, consulUrl, backendPath)},
                    'check_callback': true,
                    'plugins': ['contextmenu', 'types', 'state', 'search', 'wholerow'],
                    'core': {
                        "multiple": false,
                        "animation": 0,
                        "check_callback": true,
                        "themes": {"stripes": true},
                        'data': []
                    }
                };

                allKeys = consulUrl + "?keys";

                $('#generalValueAreaID').css("left", leftPos + 14 + "px");

                $('#renameConfirmBtnId').on('click', function () {
                    renDupNode(consulUrl, backendPath);
                });

                $('.overwriteBtn').on('click', function () {
                    var parent = localStorage['ccpObjParent'],
                        ccpObjId = localStorage['ccpObjId'],
                        ccpSourcePaths = localStorage['ccpSourcePaths'],
                        ccType = localStorage['ccpObjType'],
                        srcConsul = localStorage['ccpObjConsul'];

                    if (localStorage['overwriteFor'] === "import") {
                        importConsul($(this).data('answer'), consulUrl, backendPath);
                    } else if (localStorage['overwriteFor'] === "ccp") {
                        ccPaste(ccpSourcePaths, parent, ccpObjId, ccType, srcConsul, $(this).data('answer'), consulUrl, backendPath);
                    }
                });

                $('#valueUpdateBtnId').on('click', function () {
                    var path = $('#selectedNodeID').text(),
                        cKeyValueObj = $('#cKeyValue'),
                        value = cKeyValueObj.val();

                    cKeyValueObj.val('Loading...');
                    sendToConsul(path, value, true, true, consulUrl, userRights, backendPath)
                });

                $('#enableExportBtnId').on('click', function () {
                    localStorage['treeBackup'] = localStorage['jstree'];
                    var updateControl = $('.update-control'), tree;
                    updateControl.addClass('hidden');
                    updateControl.attr('disabled', true);
                    $('#createElementText').addClass('hidden');
                    $('#exportSelection').removeClass('hidden');
                    $('#enableExportBtnId').toggleClass('hidden');
                    $('#disableManualExport').toggleClass('hidden');
                    $('#keyValueFieldsid').remove();
                    $('#importExportBtnId').remove();
                    $('#createRootBtnId').remove();
                    $("#ConsulTree").jstree("destroy");
                    tree = {
                        'contextmenu': {'items': customMenu(false, userRights, consulUrl, backendPath)},
                        "plugins": ["checkbox", "types", "wholerow", "state", "search"],
                        'core': {
                            "multiple": true,
                            "animation": 0,
                            "check_callback": true,
                            "themes": {"stripes": true},
                            'data': []
                        }
                    };

                    getTree(tree, consulUrl, backendPath, allKeys);
                });

                $('#createKeyBtnId').on('click', function () {
                    var nodeName = $('#pathDescribeID').val();
                    var nodeValue = $('#inputKeyValueId').val(),
                        splitUpPath = nodeName.split("/"),
                        toBeCreatedPath = '', lastIsFile = false,
                        i, filteredSplitUpPath = [];

                    $.each(splitUpPath, function (key, item) {
                        if (item.length !== 0) filteredSplitUpPath.push(item);
                    });

                    if (nodeName.substr(nodeName.length - 1) !== '/') lastIsFile = true;

                    for (i = 0; i < filteredSplitUpPath.length; i++) {
                        toBeCreatedPath = toBeCreatedPath + filteredSplitUpPath[i] + "/";
                        if (i === filteredSplitUpPath.length - 1) {
                            if (lastIsFile) {
                                sendToConsul(nodeName, nodeValue, true, false, consulUrl, userRights, backendPath);
                            } else {
                                sendToConsul(toBeCreatedPath, nodeValue, true, false, consulUrl, userRights, backendPath);
                            }
                        } else {
                            sendToConsul(toBeCreatedPath, nodeValue, false, false, consulUrl, userRights, backendPath);
                        }
                    }
                });
                $('#exportSelection').on('click', function () {
                    exportConsul($("#ConsulTree").jstree(true).get_selected(), backendPath, consulTitle, consulUrl);
                });
                consulUrlSelector.on('change', function () {
                    getSetConfig(consul, true);
                    location.reload();
                });
                $('#consulFullUrlId').text(consulUrl);
                $('#searchInputId').on('keyup', function () {
                    checkClearIcon();
                    if (to) clearTimeout(to);
                    to = setTimeout(function () {
                        var v = $('#searchInputId').val();
                        $('#ConsulTree').jstree(true).search(v);
                    }, 250);
                });
                consulTreeDivID.on("select_node.jstree", function (e, data) {
                    var updateControl = $('.update-control'),
                        cKeyValueObj = $('#cKeyValue'),
                        selectedNodeText = data.node.id;

                    cKeyValueObj.parent().parent().prev().find('h5').text(selectedNodeText);
                    $('#selectedNodeID').text(selectedNodeText);
                    cKeyValueObj.val('Loading...');
                    updateFieldsToggle(true, userRights);
                    if (data.node.id.substr(-1) !== '/') {
                        updateControl.removeClass('hidden');
                        $('#createElementText').addClass('hidden');
                        getValue(data.node.id, cKeyValueObj, consulUrl, userRights, backendPath);
                    } else {
                        updateControl.addClass('hidden');
                        $('#createElementText').removeClass('hidden');
                        cKeyValueObj.val('');
                        cKeyValueObj.parent().parent().parent().removeClass('panel-warning');
                        cKeyValueObj.parent().parent().parent().removeClass('panel-success');
                        cKeyValueObj.parent().parent().parent().addClass('panel-default');
                    }
                });

                //console.log("Establishing Connection to the Consul host");
                $('#connectingModalId').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                if (localStorage['treeBackup']) {
                    localStorage['jstree'] = localStorage['treeBackup'];
                    localStorage.removeItem('treeBackup');
                }

                getTree(tree, consulUrl, backendPath, allKeys);
            }
        });
    });
</script>
</body>
</html>
