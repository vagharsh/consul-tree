<?php
$calledUrl = "$_SERVER[REQUEST_URI]";
if (strpos($calledUrl, 'backend') == false) {
    $calledLoc = '';
    $backendStatus = 'backend/';
} else {
    $calledLoc = '../';
    $backendStatus = '';
}
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
        <form class="navbar-form navbar-right">
            <div class="form-group">
                <select class="form-control" id="consulUrlSelectorId"></select>
            </div>
            <button type="button" class="btn btn-default" aria-label="Left Align" id="resetLocationBtnId"
                    data-toggle="tooltip" data-placement="bottom" title="Reset consul location settings">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
            </button>
        </form>
        <p class="navbar-text navbar-right">Consul locations</p>

    </div>
</nav>
<nav class="navbar navbar-inverse navbar-fixed-bottom">
    <div class="container">
        <p class="navbar-text navbar-lef">Consul-tree v6.2</p>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="https://github.com/vagharsh/consul-tree">GitHub Project</a></li>
        </ul>
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
                <button type="button" id="importExportBtnId" class="btn btn-primary" disabled="disabled"
                        data-toggle="modal"
                        data-target="#importExportModalId">Import
                </button>
                <button type="button" class="btn btn-warning" disabled="disabled" id="enableExportBtnId">Enable Export
                </button>
                <button type="button" class="btn btn-info hidden" disabled="disabled" id="disableManualExport">Disable
                    Export
                </button>
                <button type="button" class="btn btn-success hidden" disabled="disabled" id="exportSelection">Export
                    Selection
                </button>
                <button type="button" class="btn btn-primary" disabled="disabled" id="createRootBtnId">Create Folder /
                    Key
                </button>
            </div>
        </form>
    </div>
    <div id="ConsulTree" class="well col-md-5"></div>
    <div class="border-left" id="generalValueAreaID" style="position: fixed; width: 568px; padding-left: 15px">
        <span id="createElementText" style="color: #737373">Select a key to get its value, right-click on the tree to create an element.</span>
        <div class="form-group">
            <textarea class="form-control update-control hidden" id="cKeyValue" rows="8"></textarea>
        </div>
        <br>
        <button type="button" disabled="disabled" id="valueUpdateBtnId" class="btn btn-primary update-control hidden">Update
        </button>
        <span class="update-control hidden" style="color: #737373">&nbsp;&nbsp;To create an element, right-click on the tree.</span>
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
                    <h5 for="oldNodePathId" class="control-label">Old node name</h5>
                    <input type="text" id="oldNodePathId" value="" class="form-control" readonly="readonly">
                </div>
                <div class="form-group">
                    <h5 for="newNodePathId" class="control-label">New node name</h5>
                    <input type="text" id="newNodePathId" value="" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="renameConfirmBtnId" disabled="disabled" class="btn btn-info">Rename</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<div class="page-footer"></div>
<script>
    $(document).ready(function () {
        function extractHostname(url) {
            var hostname;
            //find & remove protocol (http, ftp, etc.) and get hostname

            if (url.indexOf("://") > -1) {
                hostname = url.split('/')[2];
            }
            else {
                hostname = url.split('/')[0];
            }

            //find & remove port number
            hostname = hostname.split(':')[0];
            //find & remove "?"
            hostname = hostname.split('?')[0];

            return hostname;
        }

        function getSetConfig(consul, modify) {
            var selectedConsulIdx = $('#consulUrlSelectorId').find(":selected").attr("data-idx"),
                stringedObj, SelectedConsul;

            stringedObj = {
                "url": consul[selectedConsulIdx].url,
                "title": consul[selectedConsulIdx].title
            };

            if (localStorage['selectedConsul']) {
                if (modify) {
                    SelectedConsul = JSON.stringify(stringedObj);
                    localStorage['selectedConsul'] = SelectedConsul;
                }
            } else {
                SelectedConsul = JSON.stringify(stringedObj);
                localStorage['selectedConsul'] = SelectedConsul;
            }
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('#pageTitle').text("Consul Tree | " + window.location.hostname);

        $.getJSON("<?php echo $calledLoc; ?>config/config.json", function (consul) {
            if (consul) {
                if (consul.length !== 0) {
                    var optionElems, consulUrl, consulTitle, selectedConsulJson,
                        consulUrlSelector = $("#consulUrlSelectorId"),
                        renameModalObj = $('#renameModalId'),
                        createFolderCallee;

                    for (var elem in consul) {
                        optionElems += '<option value="' + consul[elem].url + '" data-idx="' + elem + '">' + extractHostname(consul[elem].url) + '</option>';
                    }
                    consulUrlSelector.html(optionElems);

                    getSetConfig(consul);

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
                        leftPos = consulTreeDivID.outerWidth() + consulTreeDivID.offset().left;

                    $('#generalValueAreaID').css("left", leftPos + 14 + "px");

                    Array.prototype.contains = function (v) {
                        for (var i = 0; i < this.length; i++) {
                            if (this[i] === v) return true;
                        }
                        return false;
                    };
                    Array.prototype.unique = function () {
                        var arr = [];
                        for (var i = 0; i < this.length; i++) {
                            if (!arr.contains(this[i])) {
                                arr.push(this[i]);
                            }
                        }
                        return arr;
                    };

                    function resetLocationStorage() {
                        localStorage.removeItem('selectedConsul');
                        localStorage.removeItem('ccpObjType');
                        localStorage.removeItem('ccpObjPaths');
                        localStorage.removeItem('ccpObjParent');
                        localStorage.removeItem('ccpObjConsul');
                        location.reload();
                    }

                    function importConsul() {
                        var files = document.getElementById('jsonInputFile').files;
                        if (files.length <= 0) {
                            alert('JSON file must be selected first.');
                            return false;
                        }

                        var fr = new FileReader();
                        fr.onload = function (e) {
                            var result = JSON.parse(e.target.result);
                            $('#importExportModalId').modal('hide');
                            $('#processingMdlID').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $.ajax({
                                method: "POST",
                                url: "<?php echo $backendStatus; ?>requests.php",
                                data: {
                                    path: consulUrl,
                                    method: "BulkIMPORT",
                                    value: JSON.stringify(result)
                                }
                            }).done(function () {
                                location.reload();
                            });
                        };
                        fr.readAsText(files.item(0));
                    }

                    function exportConsul(data) {
                        var newData = JSON.stringify(data);

                        $('#processingMdlID').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $.ajax({
                            method: "POST",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            dataType: "json",
                            data: {
                                consul: consulUrl,
                                method: "EXPORT",
                                path: newData
                            }
                        }).done(function (data) {
                            if (data.length !== 0) {
                                var contentType = 'text/json';
                                var jsonFile = new Blob([JSON.stringify(data)], {type: contentType});
                                var a = document.createElement('a');
                                a.download = consulTitle.toLowerCase().replace(' ', '-') + '-exported.json';
                                a.href = window.URL.createObjectURL(jsonFile);
                                a.textContent = 'Download Consul-Tree-data';
                                a.dataset.downloadurl = [contentType, a.download, a.href].join(':');
                                document.body.appendChild(a);
                                a.click();
                                a.remove();
                            }
                            $('#processingMdlID').modal('hide');
                        });
                    }

                    function parseCustomJson(data, tree, file) {
                        if (file) {
                            tree.core.data.push({
                                "id": data,
                                "parent": "#",
                                "text": data,
                                'icon': 'jstree-file'
                            })
                        } else {
                            var minlen = -1,
                                picked = "", i;
                            for (i = 0; i < data.length; i++) {
                                if (data[i].length < minlen || minlen == -1) {
                                    minlen = data[i].length;
                                    picked = data[i];
                                }
                            }

                            tree.core.data.push({"id": picked, "parent": "#", "text": picked.slice(0, -1)});
                            var xdata = data;
                            xdata.splice(xdata.indexOf(picked), 1);

                            for (i = 0; i < xdata.length; i++) {
                                var name = xdata[i];
                                var parent = "";
                                if (name.substr(name.length - 1, 1) == '/') {
                                    var xname = name.substr(0, name.length - 1);
                                    parent = xname.substr(0, xname.lastIndexOf("/") + 1)
                                } else {
                                    parent = name.substr(0, name.lastIndexOf("/") + 1)
                                }

                                var filename = name.match(/([^\/]*)\/*$/)[1];

                                if (name.substr(-1) == '/') {
                                    tree.core.data.push({"id": name, "parent": parent, "text": filename})
                                } else {
                                    tree.core.data.push({
                                        "id": name,
                                        "parent": parent,
                                        "text": filename,
                                        'icon': 'jstree-file'
                                    })
                                }
                            }
                        }
                        return tree;
                    }

                    function check4Roots(data) {
                        var roots = [], arrays = [],
                            filteredSplittedPath;

                        $.each(data, function (key, item) {
                            filteredSplittedPath = item.split("/");
                            if (item.indexOf("/") == -1) {
                                roots.push(item);
                            }
                            if (filteredSplittedPath.length !== 1){
                                if (filteredSplittedPath[1].length  == 0) {
                                    roots.push(item);
                                }
                            }
                        });
                        for (var i = 0; i < roots.length; i++) {
                            var stringLen = roots[i].length,
                                list = [];
                            $.each(data, function (key, item) {
                                var firstChars = item.substring(0, stringLen);
                                if (firstChars === roots[i]) {
                                    list.push(item);
                                }
                            });
                            arrays.push(list);
                        }
                        return arrays;
                    }

                    function getTree(tree) {
                        $.ajax({
                            method: "GET",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            dataType: 'json',
                            data: {
                                path: allKeys
                            }
                        }).done(function (data) {
                            $('#connectingModalId').modal('hide');
                            if (data['data'] == '[]') {
                                $('#noTreeModalId').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                var createRootBtnObj = $('#createRootBtnId');
                                //console.log("No Data was found on Consul");
                                $('#searchInputId').attr('disabled', true);
                                $('#enableExportBtnId').attr('disabled', true);
                                createRootBtnObj.removeClass('hidden');
                                $('#importExportBtnId').attr('disabled', false);
                                createRootBtnObj.attr('disabled', false);
                            } else if (data['http_code'] !== 200) {
                                $('#consulUrlId').text(extractHostname(consulUrl));
                                $('#noConnectionModalId').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                //console.log("No Connection to the Consul host");
                                $('#searchInputId').attr('disabled', true);
                                $('#enableExportBtnId').attr('disabled', true);
                                $('#importExportBtnId').attr('disabled', true);
                                $('#createRootBtnId').attr('disabled', true);
                                $('#disableManualExport').attr('disabled', true);
                                $('#exportSelection').attr('disabled', true);
                            } else {
                                var realData = JSON.parse(data['data']);
                                $('#searchInputId').attr('disabled', false);
                                $('#importExportBtnId').attr('disabled', false);
                                $('#createRootBtnId').attr('disabled', false);
                                $('#enableExportBtnId').attr('disabled', false);
                                $('#disableManualExport').attr('disabled', false);
                                $('#exportSelection').attr('disabled', false);

                                realData = realData.sort();

                                if (dataValidation(realData)) {
                                    var el = check4Roots(realData), obj, file;
                                    for (var i = 0; i < el.length; i++) {
                                        obj = el[i];
                                        file = false;
                                        if (obj.length == 1) {
                                            if (obj[0].indexOf("/") == -1) {
                                                obj = obj[0];
                                                file = true;
                                            }
                                        }
                                        tree = parseCustomJson(obj, tree, file);
                                    }
                                    //console.log("Drawing the Tree");
                                    $('#ConsulTree').jstree(tree);
                                }
                            }
                        });
                    }

                    function updateFieldsToggle(readonly){
                        var cKeyValue = $('#cKeyValue'),
                            updateBtnId = $('#valueUpdateBtnId');

                        if (! readonly){
                            cKeyValue.parent().removeClass('has-warning');
                            cKeyValue.attr('readonly', false);
                            updateBtnId.removeClass('disabled');
                            updateBtnId.attr('disabled', false);
                        } else {
                            cKeyValue.parent().addClass('has-warning');
                            cKeyValue.attr('readonly', true);
                            updateBtnId.addClass('disabled');
                            updateBtnId.attr('disabled', true);
                        }
                    }

                    function getValue(path, obj) {
                        path = consulUrl + path + "?raw";
                        $.ajax({
                            method: "GET",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            data: {
                                path: path
                            }
                        }).done(function (data) {
                            var realData = JSON.parse(data);
                            if (obj) {
                                obj.text(realData['data']);
                                obj.val(realData['data']);
                            } else {
                                $('#gotNodeValue').text(realData['data']);
                            }
                            updateFieldsToggle(false);
                        });
                    }

                    function ccPaste(path, parentId, replace, ccType, srcConsul) {
                        $.ajax({
                            method: "POST",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            data: {
                                method: "CCP",
                                consul: consulUrl,
                                parentId: parentId,
                                replace: replace,
                                ccType: ccType,
                                srcConsul: srcConsul,
                                path: path
                            }
                        }).done(function () {
                            localStorage.removeItem('ccpObjType');
                            localStorage.removeItem('ccpObjPaths');
                            localStorage.removeItem('ccpObjParent');
                            localStorage.removeItem('ccpObjConsul');
                            location.reload();
                        })
                    }

                    function sendToConsul(path, value, reload, updateBtn) {
                        path = path.replace(/\\/g, '/');

                        if (updateBtn){
                            updateFieldsToggle(true);
                        }

                        if (path[0] == '/') {
                            path = path.substring(1);
                        }
                        var fullPath = consulUrl + path;
                        $.ajax({
                            method: "POST",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            data: {
                                method: "PUT",
                                path: fullPath,
                                value: value
                            }
                        }).done(function () {
                            if (reload) {
                                if (updateBtn) {
                                    updateFieldsToggle(false);
                                    $('#cKeyValue').val(value);
                                } else {
                                    location.reload();
                                }
                            }
                        })
                    }

                    function deleteNode(path) {
                        $.ajax({
                            method: "POST",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            data: {
                                method: "DELETE",
                                consul: consulUrl,
                                path: path
                            }
                        }).done(function () {
                            location.reload();
                        })
                    }

                    $('#renameConfirmBtnId').on('click', function () {
                        $('#renameModalId').modal('hide');
                        renameNode();
                    });
                    $('#newNodePathId').on('keyup', function () {
                        var newText = $('#newNodePathId').val();
                        if (newText.length >= 1) {
                            $('#renameConfirmBtnId').attr('disabled', false);
                        } else {
                            $('#renameConfirmBtnId').attr('disabled', true);
                        }
                    });

                    function renameNode() {
                        var newNodeName = $('#newNodePathId').val(),
                            objChildren = workingObj['children_d'],
                            objParent = workingObj['parent'],
                            oldObjPath = workingObj['id'],
                            newNodePath, newNodeString, newData = {};

                        $('#processingMdlID').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        setTimeout(function () {
                            if (oldObjPath.substr(oldObjPath.length - 1) !== '/') {
                                newNodeString = objParent + newNodeName;
                                newNodePath = oldObjPath.replace(oldObjPath, newNodeString);
                                newData[oldObjPath] = newNodePath;
                            } else {
                                $.each(objChildren, function (key, item) {
                                    newNodeString = objParent + newNodeName + '/';
                                    newNodePath = item.replace(oldObjPath, newNodeString);
                                    newData[item] = newNodePath;
                                });
                            }
                            $.ajax({
                                method: "POST",
                                url: "<?php echo $backendStatus; ?>requests.php",
                                data: {
                                    consul: consulUrl,
                                    method: "RENAME",
                                    path: JSON.stringify(newData),
                                    selectedObj: oldObjPath
                                }
                            }).done(function () {
                                location.reload();
                            });
                        }, 250);
                    }

                    function customMenu(node) {
                        var items = {
                            "create": {
                                "separator_before": false,
                                "separator_after": true,
                                "_disabled": false,
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
                                "label": "Rename",
                                "action": function (data) {
                                    workingInst = $.jstree.reference(data.reference);
                                    workingObj = workingInst.get_node(data.reference);

                                    $('#oldNodePathId').val(workingObj['text']);
                                    $('#renameModalId').modal('show');
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
                                        "action": function (data) {
                                            var inst = $.jstree.reference(data.reference),
                                                obj = inst.get_node(data.reference), srcPath;

                                            srcPath = obj['children_d'];
                                            srcPath = srcPath.concat([obj['id']]);

                                            localStorage['ccpObjPaths'] = JSON.stringify(srcPath);
                                            localStorage['ccpObjParent'] = obj.parent;
                                            localStorage['ccpObjType'] = 'cut';
                                            localStorage['ccpObjConsul'] = consulUrl;
                                        }
                                    },
                                    "copy": {
                                        "separator_before": false,
                                        "icon": false,
                                        "separator_after": false,
                                        "label": "Copy",
                                        "action": function (data) {
                                            var inst = $.jstree.reference(data.reference),
                                                obj = inst.get_node(data.reference), srcPath;

                                            srcPath = obj['children_d'];
                                            srcPath = srcPath.concat([obj['id']]);

                                            localStorage['ccpObjPaths'] = JSON.stringify(srcPath);
                                            localStorage['ccpObjParent'] = obj.parent;
                                            localStorage['ccpObjType'] = 'copy';
                                            localStorage['ccpObjConsul'] = consulUrl;
                                        }
                                    },
                                    "paste": {
                                        "separator_before": false,
                                        "icon": false,
                                        "_disabled": function (data) {
                                            return !(localStorage['ccpObjPaths'] && localStorage['ccpObjPaths'].length > 0);
                                        },
                                        "separator_after": false,
                                        "label": "Paste",
                                        "action": function (data) {
                                            $('#processingMdlID').modal({
                                                backdrop: 'static',
                                                keyboard: false
                                            });

                                            var inst = $.jstree.reference(data.reference),
                                                obj = inst.get_node(data.reference),
                                                srcPath = JSON.parse(localStorage['ccpObjPaths']),
                                                parent = localStorage['ccpObjParent'],
                                                ccType = localStorage['ccpObjType'],
                                                srcConsul = localStorage['ccpObjConsul'];


                                            $.each(srcPath, function (key, item) {
                                                item = item.replace(/\\/g, '/');
                                                srcPath[key] = item;
                                            });

                                            setTimeout(function () {
                                                ccPaste(JSON.stringify(srcPath), parent, obj.id, ccType, srcConsul);
                                            }, 250);
                                        }
                                    }
                                }
                            },
                            "remove": {
                                "separator_before": true,
                                "icon": false,
                                "separator_after": false,
                                "_disabled": false,
                                "label": "Delete",
                                "action": function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference),
                                        srcPath = [];

                                    if (obj['id'].slice(-1) === '/'){
                                        srcPath = obj['children_d'];
                                    }

                                    srcPath.push(obj['id']);
                                    srcPath = JSON.stringify(srcPath);

                                    if (confirm('Are you sure you want to DELETE ' + obj.id + ' ?')) {
                                        $('#processingMdlID').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        setTimeout(function () {
                                            deleteNode(srcPath);
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

                    function check4Key() {
                        var inputObj = $('#keyInputId');
                        var inputObjLength = inputObj.val().length;
                        if (inputObjLength > 0) {
                            $('#inputKeyValueId').attr('disabled', false);
                            $('#createKeyBtnId').attr('disabled', false);
                        } else {
                            $('#inputKeyValueId').attr('disabled', true);
                            $('#createKeyBtnId').attr('disabled', true);
                        }
                    }

                    function fixTree(data) {
                        if (data !== undefined) {
                            data = JSON.stringify(data)
                        }
                        $.ajax({
                            method: "POST",
                            url: "<?php echo $backendStatus; ?>requests.php",
                            data: {
                                method: "FIX",
                                consul: consulUrl,
                                path: data
                            }
                        }).done(function () {
                            location.reload();
                        });
                    }

                    function getFoldersOnly(data) {
                        var onlyFolders = [], uniqueFolders;
                        $.each(data, function (key, item) {
                            if (item.substr(item.length - 1) !== '/') {
                                item = item.split('/').slice(0, -1).join('/');
                                item = item + '/';
                            }
                            onlyFolders.push(item);
                        });

                        uniqueFolders = onlyFolders.unique();
                        return uniqueFolders;
                    }

                    function explodeData(data) {
                        var newData = [], obj = '';
                        $.each(data, function (key, item) {
                            obj = item;
                            while (obj !== '') {
                                obj = obj.split('/').slice(0, -1).join('/');
                                if (obj !== '') {
                                    newData.push(obj + '/');
                                }
                            }
                        });
                        return newData;
                    }

                    function dataValidation(data) {
                        //console.log("Validating data structure");
                        var tobeFixedData = [], i = 0, onlyFolders,
                            explodedData, uniqueFolders,
                            loadingModalObj = $('#loadingTreeMdlID'),
                            status = true;

                        loadingModalObj.modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        onlyFolders = getFoldersOnly(data);
                        explodedData = explodeData(onlyFolders);
                        uniqueFolders = explodedData.unique();

                        for (i = 0; i < uniqueFolders.length; i++) {
                            if (data.indexOf(uniqueFolders[i]) == -1) {
                                tobeFixedData.push(uniqueFolders[i]);
                            }
                        }

                        if (tobeFixedData.length > 0) {
                            //console.log("Fixing data structure");
                            fixTree(tobeFixedData);
                            status = false;
                        } else {
                            loadingModalObj.modal('hide');
                        }

                        return status;
                    }

                    function checkClearIcon() {
                        var searchClearIcon = $('#searchclear');
                        if ($('#searchInputId').val().length > 0) {
                            searchClearIcon.removeClass('glyphicon-search');
                            searchClearIcon.addClass('glyphicon-remove');
                        } else {
                            searchClearIcon.removeClass('glyphicon-remove');
                            searchClearIcon.addClass('glyphicon-search');
                        }
                    }

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
                            cKeyValue = $('#cKeyValue'),
                            value = cKeyValue.val();

                        cKeyValue.val('Loading...');
                        sendToConsul(path, value, true, true)
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

                        getTree(tree);
                    });
                    $('#disableManualExport').on('click', function () {
                        if (localStorage['treeBackup']) {
                            localStorage['jstree'] = localStorage['treeBackup'];
                            localStorage.removeItem('treeBackup');
                        }
                        location.reload();
                    });
                    $('#importConsulBtnId').on('click', importConsul);
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
                            if (i == filteredSplittedPath.length -1) {
                                if (lastIsFile){
                                    sendToConsul(nodeName, nodeValue, true);
                                } else {
                                    sendToConsul(toBeCreatedPath, nodeValue, true);
                                }
                            } else {
                                sendToConsul(toBeCreatedPath, nodeValue, false);
                            }
                        }
                    });
                    $('#exportSelection').on('click', function () {
                        exportConsul($("#ConsulTree").jstree(true).get_selected());
                    });
                    consulUrlSelector.on('change', function () {
                        getSetConfig(consul, true);
                        location.reload();
                    });
                    $('#consulFullUrlId').text(consulUrl);
                    $('#keyInputId').on('keyup', function () {
                        check4Key();
                        var keyValueInput = $('.inputKeyValueClass');
                        if ($(this).val().slice(-1) == "/") {
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
                            keyValueTextArea = $('#cKeyValue');

                        $('#selectedNodeID').text(data.node.id);
                        keyValueTextArea.val('Loading...');
                        updateFieldsToggle(true);
                        if (data.node.id.substr(-1) != '/') {
                            updateControl.removeClass('hidden');
                            $('#createElementText').addClass('hidden');
                            getValue(data.node.id, keyValueTextArea);
                        } else {
                            updateControl.addClass('hidden');
                            $('#createElementText').removeClass('hidden');
                            keyValueTextArea.val('');
                        }
                    });
                    if (localStorage['treeBackup']) {
                        localStorage['jstree'] = localStorage['treeBackup'];
                        localStorage.removeItem('treeBackup');
                    }

                    getTree(tree);
                }
            }
        });
    });
</script>
</body>
</html>
