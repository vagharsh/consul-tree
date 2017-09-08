<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo "Consul Tree | " . $_SERVER['HTTP_HOST'] ?></title>
    <style>
        html {
            margin: 0;
            padding: 0;
            font-size: 62.5%;
        }

        body {
            margin: 0 auto;
            padding: 20px 10px;
            font-size: 14px;
            font-size: 1.4em;
        }

        body > .container {
            padding: 20px 15px 0;
        }

        h1 {
            font-size: 1.8em;
        }

        .padded-right-middle {
            margin-right: 15px;
        }

        #ConsulTree {
            overflow-x: auto;
        }

        .page-header {
            border-bottom: 0 !important;
            padding-bottom: 0 !important;
        }

        .jstree-container-ul {
            margin-right: 15px !important;
        }

        #searchclear {
            position: absolute;
            right: 25px;
            top: 0;
            bottom: 0;
            height: 26px;
            margin: auto;
            font-size: 14px;
            cursor: pointer;
            color: #ccc;
        }

    </style>
    <link rel="stylesheet" href="lib/themes/default/style.min.css"/>
    <link rel="shortcut icon" type="image/png" href="lib/_favicon.png"/>
    <link href="lib/css/bootstrap.min.css" rel="stylesheet">
    <script src="lib/js/jquery-3.2.1.min.js"></script>
    <script src="lib/js/bootstrap.min.js"></script>
    <script src="lib/js/jstree.js"></script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#" id="consulTitleID"></a>
        </div>
    </div>
</nav>
<nav class="navbar navbar-inverse navbar-fixed-bottom">
    <div class="container">
        <p class="navbar-text navbar-lef">Consul-tree v5.2 | Updated
            on: <?php echo date("F d Y", filemtime('index.php')); ?></p>
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
                <button type="button" id="importExportBtnId" class="btn btn-primary" data-toggle="modal"
                        data-target="#importExportModalId">Import
                </button>
                &nbsp;
                <button type="button" class="btn btn-warning" id="enableExportBtnId">Enable Export</button>
                <button type="button" class="btn btn-info hidden" id="disableManualExport">Disable Export
                </button>
                <button type="button" class="btn btn-success hidden" id="exportSelection">Export Selection</button>
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
        <button type="button" id="valueUpdateBtnId" class="btn btn-primary update-control hidden">Update
        </button>
        <span class="update-control hidden" style="color: #737373">&nbsp;&nbsp;To create an element, right-click on the tree.</span>
    </div>
</div>

<div class="modal fade" id="createNodeModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
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
                        <textarea class="form-control" id="pathDescribeID" readonly>@</textarea>
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
            <div class="modal-header modal-header-danger">
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
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>No Data</strong></h4>
            </div>
            <div class="modal-body">
                <span><strong>No Data</strong> was found on Consul, <strong>Create</strong> a <strong>root</strong> form the consul-ui, or <strong>Import</strong> an existing Tree form a previous export</span>
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
            <div class="modal-body">
                <span>Check the connection between the <strong>Consul-Tree</strong> and <strong id="consulUrlId"></strong> and then try again.</span>
            </div>
            <div class="modal-footer">
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
            <div class="modal-header" style="border-bottom: 0px">
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
<p class="hidden" id="ajaxReturnFieldID"></p>
<p class="hidden" id="ajaxReturnVFieldID"></p>
<p class="hidden" id="ccTypeFieldID"></p>
<p class="hidden" id="ccParentFieldID"></p>
<p class="hidden" id="selectedNodeID"></p>
<p class="hidden" id="gotNodeValue"></p>
<a id="downloadAnchorElem" style="display:none"></a>
<div class="page-footer">

</div>
<script>
    $(document).ready(function () {
        var consulUrl = null,
            consulTitle = null;

        <?php
        require './config.php';
        ?>

        // there should be a blank line before the next var, so that the contents of the config.php won't destroy anything.
        var tree = {
            'contextmenu': {'items': customMenu},
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
            leftPos = consulTreeDivID.outerWidth() + consulTreeDivID.offset().left ;

        Array.prototype.contains = function(v) {
            for(var i = 0; i < this.length; i++) {
                if(this[i] === v) return true;
            }
            return false;
        };

        Array.prototype.unique = function() {
            var arr = [];
            for(var i = 0; i < this.length; i++) {
                if(!arr.contains(this[i])) {
                    arr.push(this[i]);
                }
            }
            return arr;
        };

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
                    url: "api/requests.php",
                    data: {
                        url: consulUrl,
                        method: "BulkIMPORT",
                        value: JSON.stringify(result)
                    }
                }).done(function (data) {
                    location.reload();
                });
            };
            fr.readAsText(files.item(0));
        }

        function exportConsul(obj) {
            getTree(tree, false, false);
            var srcPath, arr = [], type, value, dataStr, dlAnchorElem;

            if (!obj) {
                srcPath = JSON.parse($('#ajaxReturnFieldID').text());
            } else {
                srcPath = obj;
            }
            $('#processingMdlID').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                method: "POST",
                url: "api/requests.php",
                data: {
                    consulUrl: consulUrl,
                    method: "EXPORT",
                    value: JSON.stringify(srcPath)
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

        function parseCustomJson(data, tree) {
            var minlen = -1,
                picked = "", i;
            for (i = 0; i < data.length; i++) {
                if (data[i].length < minlen || minlen == -1) {
                    minlen = data[i].length;
                    picked = data[i];
                }
            }

            tree.core.data.push({"id": picked, "parent": "#", "text": picked});
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
                    tree.core.data.push({"id": name, "parent": parent, "text": filename, 'icon': 'jstree-file'})
                }
            }
            return tree;
        }

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

        function getTree(tree, generateTree, path) {
            if (path == undefined || path == false) {
                path = allKeys;
            } else {
                path = path + "?keys";
            }

            $.ajax({
                method: "POST",
                url: "api/requests.php",
                dataType: 'json',
                data: {
                    method: "GET",
                    url: path
                }
            }).done(function (data) {
                $('#connectingModalId').modal('hide');
                if (data['data'] == '[]') {
                    $('#noTreeModalId').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    //console.log("No Data was found on Consul");
                    $('#searchInputId').attr('disabled', true);
                    $('#enableExportBtnId').attr('disabled', true);
                } else if (data['http_code'] !== 200){
                    $('#consulUrlId').text(extractHostname(consulUrl));
                    $('#noConnectionModalId').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    //console.log("No Connection to the Consul host");
                    $('#searchInputId').attr('disabled', true);
                    $('#enableExportBtnId').attr('disabled', true);
                    $('#importExportBtnId').attr('disabled', true);
                } else {
                    var realData = JSON.parse(data['data']);
                    $('#searchInputId').attr('disabled', false);
                    $('#enableExportBtnId').attr('disabled', false);
                    realData = realData.sort();
                    if (generateTree == true) {
                        dataValidation(realData);
                        tree = parseCustomJson(realData, tree);
                        //console.log("Drawing the Tree");
                        $('#ConsulTree').jstree(tree);
                    } else {
                        $('#ajaxReturnFieldID').text(JSON.stringify(realData));
                    }
                }
            });
        }

        function getValue(path, obj) {
            path = consulUrl + path + "?raw";
            $.ajax({
                method: "POST",
                url: "api/requests.php",
                data: {
                    method: "GET",
                    url: path
                }
            }).done(function (data) {
                var realData = JSON.parse(data);
                if (obj) {
                    obj.text(realData['data']);
                    obj.val(realData['data']);
                } else {
                    $('#gotNodeValue').text(realData['data']);
                }
                obj.parent().removeClass('has-warning');
                obj.attr('readonly', false)
            });
        }

        function ccPaste(paths, parentId, replace) {
            $.ajax({
                method: "POST",
                url: "api/requests.php",
                data: {
                    method: "CCP",
                    consulUrl: consulUrl,
                    parentId: parentId,
                    replace: replace,
                    url: JSON.stringify(paths)
                }
            }).done(function () {
                dataValidation();
            })
        }

        function sendToConsul(path, value, reload) {
            path = path.replace(/\\/g, '/');
            var fullPath = consulUrl + path;
            $.ajax({
                method: "POST",
                url: "api/requests.php",
                data: {
                    method: "PUT",
                    url: fullPath,
                    value: value
                }
            }).done(function () {
                if (reload !== undefined || reload !== false) {
                    location.reload();
                }
            })
        }

        function deleteNode(path) {
            var getPath = consulUrl + path;
            getTree(tree, false, getPath);
            var myJsonString = $('#ajaxReturnFieldID').text();
            $.ajax({
                method: "POST",
                url: "api/requests.php",
                data: {
                    method: "DELETE",
                    consulUrl: consulUrl,
                    url: myJsonString
                }
            }).done(function () {
                location.reload();
            })
        }

        function customMenu(node) {
            var items = {
                "create": {
                    "separator_before": false,
                    "separator_after": true,
                    "_disabled": false, //(this.check("create_node", data.reference, {}, "last")),
                    "label": "Create",
                    "action": function (data) {
                        workingInst = $.jstree.reference(data.reference);
                        workingObj = workingInst.get_node(data.reference);

                        $('#pathDescribeID').text(workingObj.id);
                        $('#createNodeModalId').modal('show');
                    }
                },
                "cut": {
                    "separator_before": true,
                    "separator_after": false,
                    "label": "Cut",
                    "action": function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        $('#ccTypeFieldID').text('cut');
                        $('#ccParentFieldID').text(obj.parent);

                        var path = consulUrl + obj.id;
                        getTree(false, false, path);

                        if (inst.is_selected(obj)) {
                            inst.cut(inst.get_top_selected());
                        } else {
                            inst.cut(obj);
                        }
                    }
                },
                "copy": {
                    "separator_before": false,
                    "icon": false,
                    "separator_after": false,
                    "label": "Copy",
                    "action": function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        $('#ccTypeFieldID').text('copy');
                        $('#ccParentFieldID').text(obj.parent);

                        var path = consulUrl + obj.id;
                        getTree(false, false, path);

                        if (inst.is_selected(obj)) {
                            inst.copy(inst.get_top_selected());
                        } else {
                            inst.copy(obj);
                        }
                    }
                },
                "paste": {
                    "separator_before": false,
                    "icon": false,
                    "_disabled": function (data) {
                        return !$.jstree.reference(data.reference).can_paste();
                    },
                    "separator_after": false,
                    "label": "Paste",
                    "action": function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference),
                            srcPath = JSON.parse($('#ajaxReturnFieldID').text()),
                            parent = $('#ccParentFieldID').text(),
                            ccType = $('#ccTypeFieldID').text(),
                            ajaxReturnedVFieldID = $('#ajaxReturnVFieldID');

                        $.each(srcPath, function (key, item) {
                            item = item.replace(/\\/g, '/');
                            srcPath[key] = item;
                        });

                        $('#processingMdlID').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        setTimeout(function () {
                            ccPaste(srcPath, parent, obj.id);
                        }, 500);

                        if (ccType == 'cut') {
                            srcPath = srcPath.sort();
                            deleteNode(srcPath[0]);
                        }
                    }
                },
                "remove": {
                    "separator_before": true,
                    "icon": false,
                    "separator_after": false,
                    "_disabled": false, //(this.check("delete_node", data.reference, this.get_parent(data.reference), "")),
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
                                deleteNode(obj.id);
                            }, 500);
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
            var inputLength = $('#keyInputId').val().length;
            if (inputLength > 0) {
                $('#inputKeyValueId').attr('disabled', false);
                $('#createKeyBtnId').attr('disabled', false);
            } else {
                $('#inputKeyValueId').attr('disabled', true);
                $('#createKeyBtnId').attr('disabled', true);
            }
        }

        function fixTree(data) {
            var srcPath;
            if (data == undefined || data.length == 0) {
                getTree(tree, false, false);
                srcPath = $('#ajaxReturnFieldID').text();
            } else {
                srcPath = JSON.stringify(data);
            }

            $.ajax({
                method: "POST",
                url: "api/fixTree.php",
                data: {
                    consulUrl: consulUrl,
                    urls: srcPath
                }
            }).done(function () {
                location.reload();
            });
        }

        function getFoldersOnly(data){
            var onlyFolders = [], uniqueFolders;
            $.each(data, function (key, item) {
                if (item.substr(item.length - 1) !== '/') {
                    item = item.split( '/' ).slice( 0, -1 ).join( '/' );
                    item = item + '/';
                }
                onlyFolders.push(item);
            });

            uniqueFolders = onlyFolders.unique();
            return uniqueFolders;
        }

        function explodeData(data){
            var newData = [], obj = '';
            $.each(data, function (key, item) {
                obj = item;
                while (obj !== ''){
                    obj = obj.split( '/' ).slice( 0, -1 ).join( '/' );
                    if (obj !== ''){
                        newData.push(obj + '/');
                    }
                }
            });
            return newData;
        }

        function dataValidation(data){
            //console.log("Validating data structure");
            var tobeFixedData = [], i = 0,onlyFolders, explodedData, uniqueFolders;
            if (data == undefined || data.length == 0) {
                data = $('#ajaxReturnFieldID').text();
            }

            $('#loadingTreeMdlID').modal({
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

            if (tobeFixedData.length > 0){
                //console.log("Fixing data structure");
                fixTree(tobeFixedData);
            }

            $('#loadingTreeMdlID').modal('hide');
        }

        function checkClearIcon(){
            var searchClearIcon = $('#searchclear');
            if ($('#searchInputId').val().length > 0){
                searchClearIcon.removeClass('glyphicon-search');
                searchClearIcon.addClass('glyphicon-remove');
            } else {
                searchClearIcon.removeClass('glyphicon-remove');
                searchClearIcon.addClass('glyphicon-search');
            }
        }

        $('#generalValueAreaID').css("left", leftPos + 14 + "px");
        $('#consulFullUrlId').text(consulUrl);
        $('#connectingModalId').modal({
            backdrop: 'static',
            keyboard: false
        });
        //console.log("Establishing Connection to the Consul host");

        if (consulTitle == null) {
            consulTitle = 'Consul-Tree';
        }
        $('#consulTitleID').text(consulTitle);
        $("#searchclear").click(function(){
            $("#searchInputId").val('');
            checkClearIcon();
            $('#searchInputId').trigger('keyup');
        });
        $('#createNodeModalId').on('shown.bs.modal', function () {
            $('#keyInputId').focus();
            var selectedNodePath = $('#selectedNodeID').text(), splittedArray, newPath;

            if (selectedNodePath.substr(selectedNodePath.length - 1) !== '/') {
                splittedArray = selectedNodePath.split("/");
                splittedArray.splice(splittedArray.length - 1, 1);
                newPath = splittedArray.join('/');
                selectedNodePath = newPath + '/';
            }

            $('#pathDescribeID').text(selectedNodePath);
            $('#pathInputId').val(selectedNodePath);

            check4Key();
        });
        $('#valueUpdateBtnId').on('click', function () {
            var path = $('#selectedNodeID').text();
            var value = $('#cKeyValue').val();
            sendToConsul(path, value, true)
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

            getTree(tree, true, false, false);
        });
        $('#disableManualExport').on('click', function () {
            if (localStorage['treeBackup']) {
                localStorage['jstree'] = localStorage['treeBackup'];
                localStorage.removeItem('treeBackup');
            }
            location.reload();
        });
        $('#importConsulBtnId').on('click', importConsul);
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
        $('#createKeyBtnId').on('click', function () {
            workingInst.create_node(workingObj, {}, "last", function () {
                var nodeName = $('#pathDescribeID').val();
                var nodeValue = $('#inputKeyValueId').val(),
                    splittedPath = nodeName.split("/"),
                    toBeCreatedPath = '';

                for (var i = 0; i < splittedPath.length - 1; i++) {
                    toBeCreatedPath = toBeCreatedPath + splittedPath[i] + "/";
                    // Create Folders
                    sendToConsul(toBeCreatedPath, nodeValue, false);
                }
                // Create the key
                if (nodeName.substr(nodeName.length - 1) !== '/') {
                    sendToConsul(nodeName, nodeValue, true);
                }
            });
        });
        $('#exportSelection').on('click', function () {
            exportConsul($("#ConsulTree").jstree(true).get_selected());
        });
        $('#searchInputId').keyup(function () {
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

            keyValueTextArea.parent().addClass('has-warning');
            keyValueTextArea.attr('readonly', true);
            keyValueTextArea.text('Loading...');
            keyValueTextArea.val('Loading...');

            if (data.node.id.substr(-1) != '/') {
                updateControl.attr('disabled', false);
                updateControl.removeClass('hidden');
                $('#createElementText').addClass('hidden');
                getValue(data.node.id, keyValueTextArea);
            } else {
                updateControl.addClass('hidden');
                updateControl.attr('disabled', true);
                $('#createElementText').removeClass('hidden');
                keyValueTextArea.text('');
                keyValueTextArea.val('');
            }
            $('#selectedNodeID').text(data.node.id);
        });
        if (localStorage['treeBackup']) {
            localStorage['jstree'] = localStorage['treeBackup'];
            localStorage.removeItem('treeBackup');
        }
        getTree(tree, true, false, true);
    });
</script>
</body>
</html>
