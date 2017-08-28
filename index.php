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

        .border-left {
            border-left: 1px #E6E6E6 solid;
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
        <p class="navbar-text navbar-lef">Consul-tree v4.8 | Updated
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
                    <input type="text" id="searchInputId" value="" class="input form-control" placeholder="Search"
                           style="margin:0 auto 1em auto;  padding:4px; border-radius:4px; border:1px solid silver;">
                </div>
                <button type="button" id="importExportBtnId" class="btn btn-primary" data-toggle="modal"
                        data-target="#importExportModalId">Import
                </button>
                <button type="button" class="btn btn-warning" id="enableManualExport">Enable Manual Export</button>
                <button type="button" class="btn btn-info hidden" id="disableManualExport">Disable Manual Export
                </button>
                <button type="button" class="btn btn-success hidden" id="exportSelection">Export Selection</button>
            </div>
        </form>
    </div>
    <div id="ConsulTree" class="well col-md-5 padded-right-middle"></div>
    <div class="col-md-6 border-left" style="position: fixed; left: 757px; width: 568px;">
        <span id="createElementText" style="color: #737373">Select a key to see its value, right-click on the tree to create an element.</span>
        <textarea class="form-control update-control hidden" id="cKeyValue" rows="8"></textarea>
        <br>
        <button type="button" id="valueUpdateBtnId" class="btn btn-primary update-control hidden">Update
        </button>
        <span class="update-control hidden" style="color: #737373">&nbsp;|&nbsp;To create an element, right-click on the tree.</span>
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
                <span><strong>No Data</strong> was found on Consul, <strong>Create</strong> at a root form the consul-ui, or <strong>Import</strong> an existing Tree form a previous export</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                <h5 class="modal-title text-center">Fixing the tree, please wait...</h5>
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
        }, allKeys = consulUrl + "?keys", to = false;

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

            $.each(srcPath, function (key, item) {
                var fullPath = consulUrl + item;

                if (fullPath.substr(fullPath.length - 1) !== '/') {
                    type = 'key';
                    getValue(item);
                    value = $('#gotNodeValue').text();
                } else {
                    value = null;
                    type = 'folder';
                }
                arr.push({
                    key: item,
                    type: type,
                    value: value
                });
            });

            dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(arr));
            dlAnchorElem = document.getElementById('downloadAnchorElem');
            dlAnchorElem.setAttribute("href", dataStr);
            dlAnchorElem.setAttribute("download", "consul-data.json");
            dlAnchorElem.click();
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

        function cleanArray(actual) {
            var newArray = [];
            for (var i = 0; i < actual.length; i++) {
                if (actual[i]) {
                    newArray.push(actual[i]);
                }
            }
            return newArray;
        }

        function getTree(tree, generateTree, path, firstRun) {
            if (path == undefined || path == false) {
                path = allKeys;
            } else {
                path = path + "?keys";
            }

            $.ajax({
                method: "POST",
                url: "api/requests.php",
                dataType: 'json',
                async: false,
                data: {
                    method: "GET",
                    url: path
                }
            }).done(function (data) {
                var validationCheck = checkIfDataIsValid(data);
                if (firstRun === true && validationCheck !== true) {
                    $('#loadingTreeMdlID').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    setTimeout(function () {
                        fixTree();
                    }, 2000);
                }

                if (data.length === 0) {
                    $('#noTreeModalId').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#searchInputId').attr('disabled', true);
                    $('#enableManualExport').attr('disabled', true);
                } else {
                    $('#searchInputId').attr('disabled', false);
                    $('#enableManualExport').attr('disabled', false);
                    data = data.sort();
                    if (generateTree == true) {
                        tree = parseCustomJson(data, tree);
                        $('#ConsulTree').jstree(tree);
                    } else {
                        $('#ajaxReturnFieldID').text(JSON.stringify(data));
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
                if (obj) {
                    obj.val(data);
                } else {
                    $('#gotNodeValue').text(data);
                }
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
                fixTree();
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

        function fixTree() {
            getTree(tree, false, false);
            var srcPath = $('#ajaxReturnFieldID').text();

            $.ajax({
                method: "POST",
                url: "api/import.php",
                data: {
                    consulUrl: consulUrl,
                    urls: srcPath
                }
            }).done(function () {
                location.reload();
            });
        }

        function checkIfDataIsValid(data) {
            var newArray = [], lastItem, arrayedpath, i, newPath, onlyParents = [];
            $.each(data, function (key, item) {
                if (item.substr(item.length - 1) !== '/') {
                    item = item.substr(0, item.lastIndexOf("/") + 1);
                }
                item = item.substr(0, item.lastIndexOf("/") + 1);
                newArray.push(item);
            });

            newArray = cleanArray(newArray.sort());

            for (i = 0; i < newArray.length; i++) {
                var newArray1 = [];
                if (i === 0) {
                    lastItem = newArray[0];
                } else {
                    lastItem = newArray[i - 1];
                }
                arrayedpath = newArray[i].split("/");
                $.each(arrayedpath, function (key, item) {
                    if (item.length !== 0) {
                        newArray1.push(item);
                    }
                });
                newArray1.splice(-1, 1);
                newPath = newArray1.join('/');
                onlyParents.push(newPath);
            }

            onlyParents = cleanArray(onlyParents.sort());

            var uniqueNames1 = [];
            var allArrayData = [];
            var allOnlyParents = [];

            $.each(onlyParents, function (i, el) {
                if ($.inArray(el, uniqueNames1) === -1) uniqueNames1.push(el);
            });

            $.each(uniqueNames1, function (i, el) {
                allOnlyParents.push(el + '/');
            });

            $.each(newArray, function (i, el) {
                if ($.inArray(el, allArrayData) === -1) allArrayData.push(el);
            });

            var valid = true;
            for (i = 0; i < allOnlyParents.length; i++) {
                if (allArrayData.indexOf(allOnlyParents[i]) == -1) {
                    valid = false;
                    break;
                }
            }
            return valid;
        }

        if (consulTitle != null) {
            $('#consulTitleID').text(consulTitle);
        } else {
            $('#consulTitleID').text('Consul-Tree');
        }

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
        $('#enableManualExport').on('click', function () {
            localStorage['treeBackup'] = localStorage['jstree'];
            var updateControl = $('.update-control');
            updateControl.addClass('hidden');
            updateControl.attr('disabled', true);
            $('#createElementText').addClass('hidden');
            $('#exportSelection').removeClass('hidden');
            $('#enableManualExport').toggleClass('hidden');
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
        $('#ConsulTree').on("select_node.jstree", function (e, data) {
            workingInst = $.jstree.reference(data.reference);
            var updateControl = $('.update-control');
            if (data.node.id.substr(-1) != '/') {
                updateControl.attr('disabled', false);
                updateControl.removeClass('hidden');
                $('#createElementText').addClass('hidden');
                getValue(data.node.id, $('#cKeyValue'));
            } else {
                updateControl.addClass('hidden');
                updateControl.attr('disabled', true);
                $('#createElementText').removeClass('hidden');
            }
            $('#selectedNodeID').text(data.node.id);
        });
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
            if (to) {
                clearTimeout(to)
            }
            to = setTimeout(function () {
                var v = $('#searchInputId').val();
                $('#ConsulTree').jstree(true).search(v);
            }, 250);
        });
        if (localStorage['treeBackup']) localStorage.removeItem('treeBackup');
        getTree(tree, true, false, true);
    });
</script>
</body>
</html>
