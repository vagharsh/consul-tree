<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
if(empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
    header('Location: login.php');
}
$userRights = (string)$_SESSION['rights'];
$calledUrl = "$_SERVER[REQUEST_URI]";

if (strpos($calledUrl, 'backend') == false) {
    $calledLoc = '';
    $backendStatus = 'backend/';
} else {
    $calledLoc = '../';
    $backendStatus = 'false';
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
                <select class="form-control" id="consulUrlSelectorId"></select>
            </div>
            <button type="button" class="btn btn-default" aria-label="Left Align" id="resetLocationBtnId"
                    data-toggle="tooltip" data-placement="bottom" title="Reset consul location settings">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
            </button>
            <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Logged in <?php echo $autoText; ?> as <?php echo $_SESSION['username']; ?>">Logout</button>
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
                    <input type="text" id="searchInputId" value="" class="form-control search-box" placeholder="Search"
                           style="margin:0 auto 1em auto;  padding:4px;">
                    <span id="searchclear" class="glyphicon glyphicon-search"></span>
                </div>
                <button type="button" id="importExportBtnId" class="btn btn-primary writeACL" disabled="disabled"
                        data-toggle="modal"
                        data-target="#importExportModalId">Import
                </button>
                <button type="button" class="btn btn-warning readACL" disabled="disabled" id="enableExportBtnId">Enable Export
                </button>
                <button type="button" class="btn btn-info hidden readACL" disabled="disabled" id="disableManualExport">Disable
                    Export
                </button>
                <button type="button" class="btn btn-success hidden readACL" disabled="disabled" id="exportSelection">Export
                    Selection
                </button>
                <button type="button" class="btn btn-primary writeACL" disabled="disabled" id="createRootBtnId">Create Folder /
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
                <span id="createElementText" style="color: #737373" class="readACL">Select a key to get its value<span class="writeACL">, right-click on the tree to create an element</span>.</span>
                <div class="form-group update-control">
                    <textarea class="form-control update-control hidden" id="cKeyValue" rows="8" readonly ></textarea>
                </div>
                <button type="button" disabled="disabled" id="valueUpdateBtnId" class="btn btn-primary update-control hidden writeACL">Update</button>
                <span class="update-control hidden writeACL" style="color: #737373">&nbsp;&nbsp;To create an element, right-click on the tree.</span>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createNodeModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Create Folder / Key</strong></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <h5 for="keyInputId" class="control-label">Folder / Key Name : <i>To create a folder, end the
                            key with /</i></h5>
                        <input type="text" class="form-control" id="keyInputId" value="">
                        <input type="hidden" class="form-control" id="pathInputId" value="">
                    </div>

                    <div class="form-group">
                        <h5 for="pathDescribeID" class="control-label">Path : </h5>
                        <textarea class="form-control" id="pathDescribeID" readonly></textarea>
                    </div>
                </form>
                <h5 for="inputKeyValueId" class="control-label inputKeyValueClass">Value : </h5>
                <textarea class="form-control inputKeyValueClass" id="inputKeyValueId"></textarea>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Rename Node</strong></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h5 for="oldNodePathId" class="control-label">Current node name: </h5>
                    <input type="text" id="oldNodePathId" value="" class="form-control" readonly="readonly">
                </div>
                <div class="form-group">
                    <h5 for="newNodePathId" class="control-label">New node name: </h5>
                    <input type="text" id="newNodePathId" value="" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="renameConfirmBtnId" data-type="rename" disabled="disabled" class="btn btn-info">Rename</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="overwriteModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
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
            <div class="modal-header modal-header-primary" style="border-bottom: 0px">
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
            <div class="modal-header" style="border-bottom: 0px">
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
        <p class="navbar-text navbar-lef">Consul-tree v6.6</p>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="https://github.com/vagharsh/consul-tree">GitHub Project</a></li>
        </ul>
    </div>
</footer>
<script src="<?php echo $calledLoc; ?>lib/js/consul-tree.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('#pageTitle').text("Consul Tree | " + window.location.hostname);

        $.getJSON("<?php echo $calledLoc; ?>config/config.json", function (consul) {
            if (consul) {
                if (consul.length !== 0) {
                    var optionElems, consulUrl, consulTitle, selectedConsulJson,
                        consulUrlSelector = $("#consulUrlSelectorId"),
                        renameModalObj = $('#renameModalId'),
                        userRights = "<?php echo $userRights; ?>",
                        createFolderCallee;

                    $.each(consul, function (key, elem) {
                        optionElems += '<option value="' + consul[key].url + '" data-idx="' + [key] + '">' + extractHostname(consul[key].url) + '</option>';
                    });
                    consulUrlSelector.html(optionElems);

                    getSetConfig(consul);
                    checkRights(userRights);

                    selectedConsulJson = JSON.parse(localStorage['selectedConsul']);
                    consulUrl = selectedConsulJson.url;
                    consulTitle = selectedConsulJson.title;
                    consulUrlSelector.val(consulUrl).attr("selected", "selected");
                    $('#consulTitleID').text(consulTitle);

                    var tree = {
                            'contextmenu': {'items': customMenu},
                            'check_callback': true,
                            'plugins': ['contextmenu', 'types', 'state', 'search', 'wholerow'],
                            'core': {
                                "multiple": false,
                                "animation": 0,
                                "check_callback": true,
                                "themes": {"stripes": true},
                                'data': []
                            }
                        },
                        allKeys = consulUrl + "?keys",
                        to = false,
                        consulTreeDivID = $('#ConsulTree'),
                        createNodeModalObj = $('#createNodeModalId'),
                        backendStatus = "<?php echo $backendStatus; ?>",
                        leftPos = consulTreeDivID.outerWidth() + consulTreeDivID.offset().left;

                        console.log();

                    $('#generalValueAreaID').css("left", leftPos + 14 + "px");
                    $('#renameConfirmBtnId').on('click', function () {
                        $('#renameModalId').modal('hide');
                        renDupNode(consulUrl, backendStatus);
                    });
                    $('#newNodePathId').on('keyup', function () {
                        var newText = $('#newNodePathId').val();
                        if (newText.length >= 1) {
                            $('#renameConfirmBtnId').attr('disabled', false);
                        } else {
                            $('#renameConfirmBtnId').attr('disabled', true);
                        }
                    });

                    function customMenu(node) {
                        var renameBtnObj = $('#renameConfirmBtnId');
                        var renameModalObj = $('#renameModalId');
                        var OldNodeObj = $('#oldNodePathId');
                        var items = {
                            "create": {
                                "separator_before": false,
                                "separator_after": true,
                                "_disabled": function () {
                                    return userRights.charAt(1) !== "1";
                                },
                                "label": "Create",
                                "action": function (data) {
                                    workingInst = $.jstree.reference(data.reference);
                                    workingObj = workingInst.get_node(data.reference);
                                    createFolderCallee = 'contextMenu';
                                    $('#pathDescribeID').text(workingObj.id);
                                    $('#createNodeModalId').modal('show');
                                }
                            },
                            "rename": {
                                "separator_before": false,
                                "icon": false,
                                "separator_after": true,
                                "_disabled": function () {
                                    return userRights.charAt(1) !== "1";
                                },
                                "label": "Rename",
                                "action": function (data) {
                                    workingInst = $.jstree.reference(data.reference);
                                    workingObj = workingInst.get_node(data.reference);

                                    renameBtnObj.text('Rename');
                                    renameBtnObj.attr('data-type', 'rename');
                                    renameModalObj.find('h4 strong').text('Rename Node');
                                    OldNodeObj.val(workingObj['text']);
                                    renameModalObj.modal('show');
                                }
                            },
                            "duplicate": {
                                "separator_before": false,
                                "icon": false,
                                "separator_after": true,
                                "_disabled": function () {
                                    return userRights.charAt(1) !== "1";
                                },
                                "label": "Duplicate",
                                "action": function (data) {
                                    workingInst = $.jstree.reference(data.reference);
                                    workingObj = workingInst.get_node(data.reference);

                                    renameBtnObj.text('Duplicate');
                                    renameBtnObj.attr('data-type', 'duplicate');
                                    renameModalObj.find('h4 strong').text('Duplicate Node');
                                    OldNodeObj.val(workingObj['text']);
                                    renameModalObj.modal('show');
                                }
                            },
                            "edit": {
                                "separator_before": true,
                                "separator_after": false,
                                "label": "Edit",
                                "submenu": {
                                    "cut": {
                                        "separator_before": true,
                                        "separator_after": false,
                                        "label": "Cut",
                                        "_disabled": function () {
                                                return userRights.charAt(2) !== "1";
                                        },
                                        "action": function (data) {
                                            var inst = $.jstree.reference(data.reference),
                                                obj = inst.get_node(data.reference);

                                            localStorage['ccpObjPaths'] = obj['id'];
                                            localStorage['ccpObjParent'] = obj.parent;
                                            localStorage['ccpObjType'] = 'cut';
                                            localStorage['ccpObjConsul'] = consulUrl;
                                        }
                                    },
                                    "copy": {
                                        "separator_before": false,
                                        "icon": false,
                                        "_disabled": function () {
                                            return userRights.charAt(1) !== "1";
                                        },
                                        "separator_after": false,
                                        "label": "Copy",
                                        "action": function (data) {
                                            var inst = $.jstree.reference(data.reference),
                                                obj = inst.get_node(data.reference);

                                            localStorage['ccpObjPaths'] = obj['id'];
                                            localStorage['ccpObjParent'] = obj.parent;
                                            localStorage['ccpObjType'] = 'copy';
                                            localStorage['ccpObjConsul'] = consulUrl;
                                        }
                                    },
                                    "paste": {
                                        "separator_before": false,
                                        "icon": false,
                                        "_disabled": function () {
                                            if (userRights.charAt(1) !== "1"){
                                                return true;
                                            } else {
                                                return !(localStorage['ccpObjPaths'] && localStorage['ccpObjPaths'].length > 0);
                                            }
                                        },
                                        "separator_after": false,
                                        "label": "Paste",
                                        "action": function (data) {
                                            var inst = $.jstree.reference(data.reference),
                                                obj = inst.get_node(data.reference);

                                            localStorage['ccpObjId'] = obj.id;
                                            localStorage['ccpSourcePaths'] = localStorage['ccpObjPaths'];
                                            localStorage['overwriteFor'] = "ccp";
                                            $('#overwriteModalId').modal('show');
                                        }
                                    }
                                }
                            },
                            "remove": {
                                "separator_before": true,
                                "icon": false,
                                "separator_after": false,
                                "_disabled": function () {
                                    return userRights.charAt(2) !== "1";
                                },
                                "label": "Delete",
                                "action": function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);

                                    if (confirm('Are you sure you want to DELETE ' + obj.id + ' ?')) {
                                        $('#processingMdlID').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        setTimeout(function () {
                                            deleteNode(obj['id'], consulUrl, backendStatus);
                                        }, 250);
                                    }
                                }
                            }
                        };
                        if (node.type === 'level_1') {
                            delete items.item2;
                        } else if (node.type === 'level_2') {
                            delete items.item1;
                        }
                        return items;
                    }

                    $('.overwriteBtn').on('click', function (){
                        var parent = localStorage['ccpObjParent'],
                            ccpObjId = localStorage['ccpObjId'],
                            ccpSourcePaths = localStorage['ccpSourcePaths'],
                            ccType = localStorage['ccpObjType'],
                            srcConsul = localStorage['ccpObjConsul'];

                        if (localStorage['overwriteFor'] === "import"){
                            importConsul($(this).data('answer'), consulUrl, backendStatus);
                        } else if (localStorage['overwriteFor'] === "ccp"){
                            ccPaste(ccpSourcePaths, parent, ccpObjId, ccType, srcConsul, $(this).data('answer'), consulUrl, backendStatus);
                        }
                    });

                    //console.log("Establishing Connection to the Consul host");
                    $('#connectingModalId').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    renameModalObj.on('shown.bs.modal', function () {
                        $('#newNodePathId').focus();
                    });
                    renameModalObj.on('hidden.bs.modal', function () {
                        $('#newNodePathId').val('');
                    });
                    createNodeModalObj.on('hidden.bs.modal', function () {
                        $('#keyInputId').val('');
                        $('#pathDescribeID').text('/');
                        $('#inputKeyValueId').val('');
                    });
                    createNodeModalObj.on('shown.bs.modal', function () {
                        var selectedNodePath = $('#selectedNodeID').text(), splittedArray, newPath;
                        $('#keyInputId').focus();

                        if (createFolderCallee === "contextMenu") {
                            if (selectedNodePath.substr(selectedNodePath.length - 1) !== '/') {
                                splittedArray = selectedNodePath.split("/");
                                splittedArray.splice(splittedArray.length - 1, 1);
                                newPath = splittedArray.join('/');
                                selectedNodePath = newPath + '/';
                            }
                        } else {
                            splittedArray = selectedNodePath.split("/");
                            selectedNodePath = '/';
                        }

                        $('#pathInputId').val(selectedNodePath);
                        $('#pathDescribeID').text(selectedNodePath);

                        check4Key();
                    });
                    $('#resetLocationBtnId').on('click', resetLocationStorage);
                    $('#valueUpdateBtnId').on('click', function () {
                        var path = $('#selectedNodeID').text(),
                            cKeyValueObj = $('#cKeyValue'),
                            value = cKeyValueObj.val();

                        cKeyValueObj.val('Loading...');
                        sendToConsul(path, value, true, true, consulUrl, userRights, backendStatus)
                    });
                    $('#enableExportBtnId').on('click', function () {
                        localStorage['treeBackup'] = localStorage['jstree'];
                        var updateControl = $('.update-control');
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
                        var tree = {
                            'contextmenu': {'items': customMenu},
                            "plugins": ["checkbox", "types", "wholerow", "state", "search"],
                            'core': {
                                "multiple": true,
                                "animation": 0,
                                "check_callback": true,
                                "themes": {"stripes": true},
                                'data': []
                            }
                        };

                        getTree(tree, consulUrl, backendStatus, allKeys);
                    });
                    $('#disableManualExport').on('click', function () {
                        if (localStorage['treeBackup']) {
                            localStorage['jstree'] = localStorage['treeBackup'];
                            localStorage.removeItem('treeBackup');
                        }
                        location.reload();
                    });
                    $('#importConsulBtnId').on('click', function (){
                        var files = document.getElementById('jsonInputFile').files;
                        if (files.length <= 0) {
                            alert('JSON file must be selected first.');
                            return false;
                        }
                        localStorage['overwriteFor'] = "import";
                        $('#overwriteModalId').modal('show');
                    });
                    $('#createRootBtnId').on('click', function () {
                        $('#createNodeModalId').modal('show');
                        createFolderCallee = "createRootBtnId";
                    });
                    $("#searchclear").on('click', function () {
                        var searchInputObj = $("#searchInputId");
                        searchInputObj.val('');
                        checkClearIcon();
                        searchInputObj.trigger('keyup');
                        searchInputObj.focus();
                    });
                    $('#createKeyBtnId').on('click', function () {
                        var nodeName = $('#pathDescribeID').val();
                        var nodeValue = $('#inputKeyValueId').val(),
                            splittedPath = nodeName.split("/"),
                            toBeCreatedPath = '', lastIsFile = false,
                            i, filteredSplittedPath = [];

                        $.each(splittedPath, function (key, item) {
                            if (item.length !== 0){
                                filteredSplittedPath.push(item);
                            }
                        });

                        if (nodeName.substr(nodeName.length - 1) !== '/') {
                            lastIsFile = true;
                        }

                        for ( i = 0; i < filteredSplittedPath.length; i++) {
                            toBeCreatedPath = toBeCreatedPath + filteredSplittedPath[i] + "/";
                            if (i === filteredSplittedPath.length -1) {
                                if (lastIsFile){
                                    sendToConsul(nodeName, nodeValue, true, consulUrl, userRights, backendStatus);
                                } else {
                                    sendToConsul(toBeCreatedPath, nodeValue, true, consulUrl, userRights, backendStatus);
                                }
                            } else {
                                sendToConsul(toBeCreatedPath, nodeValue, false, consulUrl, userRights, backendStatus);
                            }
                        }
                    });
                    $('#exportSelection').on('click', function () {
                        exportConsul($("#ConsulTree").jstree(true).get_selected(), backendStatus, consulTitle, consulUrl);
                    });
                    consulUrlSelector.on('change', function () {
                        getSetConfig(consul, true);
                        location.reload();
                    });
                    $('#consulFullUrlId').text(consulUrl);
                    $('#keyInputId').on('keyup', function () {
                        check4Key();
                        var keyValueInput = $('.inputKeyValueClass');
                        if ($(this).val().slice(-1) === "/") {
                            keyValueInput.addClass('hidden');
                        } else {
                            if (keyValueInput.hasClass('hidden')) {
                                keyValueInput.removeClass('hidden');
                            }
                        }
                        $('#pathDescribeID').text($('#pathInputId').val() + $('#keyInputId').val());
                    });
                    $('#searchInputId').on('keyup', function () {
                        checkClearIcon();
                        if (to) {
                            clearTimeout(to)
                        }
                        to = setTimeout(function () {
                            var v = $('#searchInputId').val();
                            $('#ConsulTree').jstree(true).search(v);
                        }, 250);
                    });
                    consulTreeDivID.on("select_node.jstree", function (e, data) {
                        workingInst = $.jstree.reference(data.reference);
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
                            getValue(data.node.id, cKeyValueObj, consulUrl, userRights, backendStatus);
                        } else {
                            updateControl.addClass('hidden');
                            $('#createElementText').removeClass('hidden');
                            cKeyValueObj.val('');
                            cKeyValueObj.parent().parent().parent().removeClass('panel-warning');
                            cKeyValueObj.parent().parent().parent().removeClass('panel-success');
                            cKeyValueObj.parent().parent().parent().addClass('panel-default');
                        }
                    });
                    if (localStorage['treeBackup']) {
                        localStorage['jstree'] = localStorage['treeBackup'];
                        localStorage.removeItem('treeBackup');
                    }
                    getTree(tree, consulUrl, backendStatus, allKeys);
                }
            }
        });
    });
</script>
</body>
</html>
