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
            max-width: 800px;
            min-width: 300px;
            margin: 0 auto;
            padding: 20px 10px;
            font-size: 14px;
            font-size: 1.4em;
        }

        h1 {
            font-size: 1.8em;
        }

        .demo {
            overflow: auto;
            border: 1px solid silver;
            min-height: 100px;
        }

        .border-left {
            border-left: 1px #E6E6E6 solid;
        }

        .padded-right-middle {
            margin-right: 20px;
        }
        
        #ConsulTree {
            overflow-x: auto;
        }

        .jstree-container-ul {
            margin-right: 15px !important;
        }
    </style>
    <link rel="stylesheet" href="lib/themes/default/style.min.css"/>
    <link rel="shortcut icon" type="image/png" href="lib/_favicon.png"/>
    <link href="lib/css/bootstrap.min.css" rel="stylesheet">
    <script src="lib/jquery-3.2.1.min.js"></script>
    <script src="lib/js/bootstrap.min.js"></script>
    <script src="lib/jstree.js"></script>
</head>
<body>

<div class="container">
    <div class="col-md-offset-1 col-md-12">
        <div class="row">
            <h2>Consul Tree</h2>
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-1 control-label">Search  : </label>
                    <div class="col-sm-8">
                        <input type="text" id="searchInputId" value="" class="input form-control" data-toggle="tooltip" data-placement="left" title="Tooltip on left" style="margin:0em auto 1em auto;  padding:4px; border-radius:4px; border:1px solid silver;">
                    </div>
                    <div class="col-sm-2">
                        <button type="button" id="importExportBtnId" class="btn btn-primary" data-toggle="modal" data-target="#importExportModalId">Import / Export</button>
                    </div>
                    <div>
                        <button type="button" id="fixTreeBtnId" class="btn btn-warning hidden">Fix Tree</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="row">
            <div id="ConsulTree" class="well col-md-5 padded-right-middle"></div>
            <div class="col-md-6 border-left" style="position: fixed; left: 610px; width: 568px;">
                <textarea class="form-control update-control hidden" id="cKeyValue" rows="8"></textarea>
                <br>
                <button type="button" id="valueUpdateBtnId" class="btn btn-primary update-control hidden" >Update</button>
                <span class="update-control hidden" style="color: #737373">&nbsp;|&nbsp;To create an element, right click on the tree.</span>
            </div>
        </div>
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
                <form class="">
                    <div class="form-group">
                        <span for="keyInputId" class="control-label">Folder / Key Name : </span>
                        <input type="text" class="form-control" id="keyInputId" value="">
                        <input type="hidden" class="form-control" id="pathInputId" value="">
                    </div>

                    <div class="form-group">
                        <span for="pathDescribeID" class=" control-label">Path : </span>
                        <textarea class="form-control" id="pathDescribeID" readonly >@</textarea>
                    </div>
                </form>

                <h5>To create a folder, end the key with /</h5>
                <textarea class="form-control" id="inputKeyValueId"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="createKeyBtnId" class="btn btn-info">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    <p id="demo"></p>
</div>

<div class="modal fade" id="importExportModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Import / Export Consul Data</strong></h4>
            </div>
            <div class="modal-body">
                <form class="">
                    <div class="form-group">
                        <label>Export Consul Data : </label>
                        <button type="button" id="exportConsulBtnId" class="btn btn-warning" >Export</button>
                    </div>
                    <div class="form-group">
                        <label>Import Consul Data : </label>
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
    <h6 style="text-align:center">Application version: 3.4 | Updated on: <?php echo date("F d Y", filemtime('index.php'));?></h6>
</div>
<script>
    $(document).ready(function () {
        <?php
            require './config.php';
        ?>

        var tree = {
                'contextmenu' : {'items' : customMenu},
                'plugins': ['contextmenu', 'types', 'state', 'search'],
                'core': {
                    "animation" : 0,
                    "check_callback": true,
                    "themes": {"stripes": true},
                    'data': []
                }
           };
        var allKeys = consulUrl + "?keys";

        function importConsul(){
            var files = document.getElementById('jsonInputFile').files;
            if (files.length <= 0) {
                alert('JSON file must be selected first.');
                return false;
            }

            var fr = new FileReader();

            fr.onload = function(e) {
                var result = JSON.parse(e.target.result), decodedValue;
                $('#processingMdlID').modal('show');
                $.ajax({
                    method: "POST",
                    url: "api/bulkImport.php",
                    data: {
                        url: consulUrl,
                        value : JSON.stringify(result)
                    }
                }).done(function (data) {
                    location.reload();
                });
            };
            fr.readAsText(files.item(0));
        }
        function exportConsul(){
            getTree(tree, false, false);

            var srcPath = JSON.parse($('#ajaxReturnFieldID').text()),
                arr = [], type, value,dataStr, dlAnchorElem;

            $.each( srcPath, function( key, item ) {
                var fullPath = consulUrl + item;

                if (fullPath.substr(fullPath.length - 1) !== '/'){
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
            var minlen = -1;
            var picked = "";
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
            var newArray = new Array();
            for (var i = 0; i < actual.length; i++) {
                if (actual[i]) {
                    newArray.push(actual[i]);
                }
            }
            return newArray;
        }
        function getTree(tree, generateTree, path, firstRun){
            if (path == undefined || path == false){
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
                if (firstRun === true && checkIfDataIsValid(data) !== true){
                    $("#fixTreeBtnId").click()
                }

                if (data.length === 0) {
                    $('#noTreeModalId').modal('show');
                    $('#searchInputId').attr('disabled', true);
                } else {
                    $('#searchInputId').attr('disabled', false);
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
        function getValue(path, obj){
            path = consulUrl + path + "?raw";

            $.ajax({
                method: "POST",
                url: "api/requests.php",
                async: false,
                data: {
                    method: "GET",
                    url: path
                }
            }).done(function (data) {
                if(obj) {
                    obj.val(data);
                } else {
                    $('#gotNodeValue').text(data);
                }
            });
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
                    value : value
                }
            }).done(function (data) {
                if (reload !== undefined || reload !== false) {
                   location.reload();
                }
            })
        }
        function deleteNode(path){
            var getPath = consulUrl + path;
            getTree(tree, false, getPath);

            var srcPath = JSON.parse($('#ajaxReturnFieldID').text());

            $.each( srcPath, function( key, item ) {
                var fullPath = consulUrl + item;
                $.ajax({
                    method: "POST",
                    url: "api/requests.php",
                    async : false,
                    data: {
                        method: "DELETE",
                        url: fullPath
                    }
                }).done(function (data) {
                })
            });
        }
        function customMenu(node) {
            var items = {
                "create" : {
                    "separator_before"	: false,
                    "separator_after"	: true,
                    "_disabled"			: false, //(this.check("create_node", data.reference, {}, "last")),
                    "label"				: "Create",
                    "action"			: function (data) {
                        workingInst = $.jstree.reference(data.reference);
                        workingObj = workingInst.get_node(data.reference);

                        $('#pathDescribeID').text(workingObj.id);
                        $('#createNodeModalId').modal('show');
                    }
                },
                "cut" : {
                    "separator_before"	: true,
                    "separator_after"	: false,
                    "label"				: "Cut",
                    "action"			: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        $('#ccTypeFieldID').text('cut');
                        $('#ccParentFieldID').text(obj.parent);

                        var path = consulUrl + obj.id;
                        getTree(false, false, path);

                        if(inst.is_selected(obj)) {
                            inst.cut(inst.get_top_selected());
                        }
                        else {
                            inst.cut(obj);
                        }
                    }
                },
                "copy" : {
                    "separator_before"	: false,
                    "icon"				: false,
                    "separator_after"	: false,
                    "label"				: "Copy",
                    "action"			: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        $('#ccTypeFieldID').text('copy');
                        $('#ccParentFieldID').text(obj.parent);

                        var path = consulUrl + obj.id;
                        getTree(false, false, path);

                        if(inst.is_selected(obj)) {
                            inst.copy(inst.get_top_selected());
                        }
                        else {
                            inst.copy(obj);
                        }
                    }
                },
                "paste" : {
                    "separator_before"	: false,
                    "icon"				: false,
                    "_disabled"			: function (data) {
                        return !$.jstree.reference(data.reference).can_paste();
                    },
                    "separator_after"	: false,
                    "label"				: "Paste",
                    "action"			: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference),
                            srcPath = JSON.parse($('#ajaxReturnFieldID').text()),
                            parent = $('#ccParentFieldID').text(),
                            ccType = $('#ccTypeFieldID').text();

                        $.each( srcPath, function( key, item ) {
                            var value = false,
                                dstPath = item.replace(parent, obj.id);

                            if (item.substr(-1) !== '/') {
                                getValue(item, $('#ajaxReturnVFieldID'));
                                value = $('#ajaxReturnVFieldID').text();
                            }

                            sendToConsul(dstPath, value, false);
                        });

                        if (ccType == 'cut') {
                            srcPath = srcPath.sort();
                            deleteNode(srcPath[0]);
                        }
                    }
                },
                "remove" : {
                    "separator_before"	: true,
                    "icon"				: false,
                    "separator_after"	: false,
                    "_disabled"			: false, //(this.check("delete_node", data.reference, this.get_parent(data.reference), "")),
                    "label"				: "Delete",
                    "action"			: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        if (confirm('Are you sure you want to DELETE '+obj.id+' ?')) {
                            deleteNode(obj.id);
                            location.reload();
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
        function check4Key (){
            var inputLength = $('#keyInputId').val().length;
            if (inputLength > 0 ){
                $('#inputKeyValueId').attr('disabled', false);
                $('#createKeyBtnId').attr('disabled', false);
            } else {
                $('#inputKeyValueId').attr('disabled', true);
                $('#createKeyBtnId').attr('disabled', true);
            }
        }
        function fixTree(){
            getTree(tree, false, false);
            var srcPath = $('#ajaxReturnFieldID').text();

            $.ajax({
                method: "POST",
                url: "api/import.php",
                async : false,
                data: {
                    consulUrl : consulUrl,
                    urls: srcPath
                }
            }).done(function () {
                location.reload();
            });
        }
        function checkIfDataIsValid(data){
            var newArray = [], lastItem, arrayedpath, newPath, array2 = [];
            $.each( data, function( key, item ) {
                if (item.substr(item.length - 1) !== '/') {
                    item = item.substr(0, item.lastIndexOf("/") + 1);
                }
                item = item.substr(0, item.lastIndexOf("/") + 1);
                newArray.push(item);
            });

            newArray = cleanArray(newArray.sort());

            for (var i = 0; i < newArray.length; i++) {
                var newArray1 = [];
                if (i === 0){
                    lastItem = newArray[0];
                } else {
                    lastItem = newArray[i-1];
                }
                arrayedpath = newArray[i].split("/");
                $.each( arrayedpath, function( key, item ) {
                    if (item.length !== 0){
                        newArray1.push(item);
                    }
                });
                newArray1.splice(-1,1);
                newPath = newArray1.join('/');
                array2.push(newPath);
            }

            array2 = cleanArray(array2.sort());

            var uniqueNames1 = [];
            var uniqueNames2 = [];
            var uniqueNames3 = [];

            $.each(array2, function(i, el){
                if($.inArray(el, uniqueNames1) === -1) uniqueNames1.push(el);
            });

            $.each(uniqueNames1, function(i, el) {
                uniqueNames3.push(el + '/');
            });

            $.each(newArray, function(i, el){
                if($.inArray(el, uniqueNames2) === -1) uniqueNames2.push(el);
            });

            var valid = true;
            for (var i = 0; i < uniqueNames3.length; i++) {
                if (uniqueNames2.indexOf(uniqueNames3[i]) == -1) {
                    valid = false;
                    break;
                }
            }
            return valid;
        }

        $('#createNodeModalId').on('shown.bs.modal', function (){
            var selectedNodePath = $('#selectedNodeID').text(), splittedArray, newPath;

            if (selectedNodePath.substr(selectedNodePath.length -1) !== '/'){
                splittedArray = selectedNodePath.split("/");
                splittedArray.splice(splittedArray.length -1, 1);
                newPath = splittedArray.join('/');
                selectedNodePath = newPath + '/';
            }

            $('#pathDescribeID').text(selectedNodePath);
            $('#pathInputId').val(selectedNodePath);

            check4Key();
        });
        $('#fixTreeBtnId').on ('click', function (){
            $('#loadingTreeMdlID').modal('show');
            setTimeout(function () {
                fixTree();
            }, 2000);
        });
        $('#valueUpdateBtnId').on('click', function (){
            var path = $('#selectedNodeID').text();
            var value = $('#cKeyValue').val();

            sendToConsul(path, value, true )
        });
        $('#exportConsulBtnId').on('click', exportConsul);
        $('#importConsulBtnId').on('click', importConsul);
        $('#ConsulTree').on("select_node.jstree", function (e, data) {
            workingInst = $.jstree.reference(data.reference);
            var updateControl = $('.update-control');

            if (data.node.id.substr(-1) != '/') {
                updateControl.attr('disabled', false);
                updateControl.removeClass('hidden');
                getValue(data.node.id, $('#cKeyValue'));
            } else {
                updateControl.addClass('hidden');
                updateControl.attr('disabled', true);
            }
            $('#selectedNodeID').text(data.node.id);
        });
        $('#keyInputId').on('click', check4Key);
        $('#keyInputId').on('keyup', function (){
            check4Key();

            if ($(this).val().slice(-1) == "/"){
                $('#inputKeyValueId').toggleClass('hidden');
            } else {
                if ($('#inputKeyValueId').hasClass('hidden')){
                   $('#inputKeyValueId').removeClass('hidden');
                }
            }
           $('#pathDescribeID').text($('#pathInputId').val() + $('#keyInputId').val());
        });
        $('#createKeyBtnId').on('click', function (){
            workingInst.create_node(workingObj, {}, "last", function () {
                var nodeName = $('#pathDescribeID').val();
                var nodeValue = $('#inputKeyValueId').val(),
                    splittedPath =  nodeName.split("/"),
                    toBeCreatedPath = '';

                for (var i = 0; i < splittedPath.length -1 ; i++) {
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

        var to = false, jsTreeObj;

        $('#searchInputId').keyup(function () {
            if (to) {clearTimeout(to)}
            to = setTimeout(function () {
                var v = $('#searchInputId').val();
                $('#ConsulTree').jstree(true).search(v);
            }, 250);
        });

        getTree(tree, true, false, true);
    });
</script>
</body>
</html>
