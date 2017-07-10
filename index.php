<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tree for Consul</title>
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
    </style>
    <link rel="stylesheet" href="lib/themes/default/style.min.css"/>
    <link rel="shortcut icon" type="image/png" href="favicon.png"/>
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
            <input type="text" id="plugins4_q" value="" class="input form-control"
                   style="margin:0em auto 1em auto;  padding:4px; border-radius:4px; border:1px solid silver;">
        </div>
        <div class="row">
            <div id="lazy" class="well col-md-5 padded-right-middle"></div>
            <div class="col-md-6 border-left">
                <textarea class="form-control" id="cKeyValue" rows="8"></textarea>
                <br>
                <button type="button" id="valueUpdateBtnId" class="btn btn-primary" >Update</button>
            </div>
        </div>
        <button type="button" id="fixJsonDataBtnID" class="btn btn-danger" >Fix Tree</button>
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
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">@</span>
                    <input type="text" class="form-control" id="keyInputId" aria-describedby="basic-addon1" value="">
                </div>
                <h5>To create a folder, end the key with /</h5>
                <textarea class="form-control" id="inputKeyValueId"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="createKeyBtnId" class="btn btn-info">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<p class="hidden" id="ajaxReturnFieldID"></p>
<p class="hidden" id="ajaxReturnVFieldID"></p>
<p class="hidden" id="ccTypeFieldID"></p>
<p class="hidden" id="ccParentFieldID"></p>
<p class="hidden" id="selectedNodeID"></p>

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
        function getTree(tree, generateTree, path){
            if (path == undefined || path == false){
                path = allKeys;
            } else {
                path = path + "?keys";
            }

            $.ajax({
                method: "POST",
                url: "requests.php",
                dataType: 'json',
                async: false,
                data: {
                    method: "GET",
                    url: path
                }
            }).done(function (data) {
                data = data.sort();
                if (generateTree == true){
                    tree = parseCustomJson(data, tree);
                    $('#lazy').jstree(tree);
                } else {
                    $('#ajaxReturnFieldID').text(JSON.stringify(data));
                }
            })
        }
        function getValue(path, obj){
            path = consulUrl + path + "?raw";

            $.ajax({
                method: "POST",
                url: "requests.php",
                async: false,
                data: {
                    method: "GET",
                    url: path
                }
            }).done(function (data) {
                obj.text(data);
            });
        }
        function sendToConsul(path, value, reload) {
            path = path.replace(/\//g, '');
            path = path.replace(/\\/g, '/');

            var fullPath = consulUrl + path;

            $.ajax({
                method: "POST",
                url: "requests.php",
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
            getTree(false, false, path);
            var srcPath = JSON.parse($('#ajaxReturnFieldID').text());

            $.each( srcPath, function( key, item ) {
                var fullPath = consulUrl + item;

                $.ajax({
                    method: "POST",
                    url: "requests.php",
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
                        var inst = $.jstree.reference(data.reference);
                        var obj = inst.get_node(data.reference);

                        $('#basic-addon1').text(obj.id);
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
                            deleteNode(srcPath.slice(-1)[0]);
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

        $('#createNodeModalId').on('shown.bs.modal', function (){
            $('#basic-addon1').text($('#selectedNodeID').text());
        });

        $('#valueUpdateBtnId').on('click', function (){
            var path = $('#selectedNodeID').text();
            var value = $('#cKeyValue').val();

            sendToConsul(path, value, true )
        });

        $('#keyInputId').on('keyup', function (){
           if ($(this).val().slice(-1) == "/"){
               $('#inputKeyValueId').toggleClass('hidden');
           } else {
               if ($('#inputKeyValueId').hasClass('hidden')){
                   $('#inputKeyValueId').removeClass('hidden');
               }
           }
        });

        $('#fixJsonDataBtnID').on ('click', function (){
            getTree(tree, false, false);
            var srcPath = $('#ajaxReturnFieldID').text();

            $.ajax({
                method: "POST",
                url: "import.php",
                async : false,
                data: {
                    consulUrl : consulUrl,
                    urls: srcPath
                }
            }).done(function (data) {
                location.reload();
            })
        });

        var to = false, jsTreeObj;
        $('#plugins4_q').keyup(function () {
            if (to) {
                clearTimeout(to);
            }
            to = setTimeout(function () {
                var v = $('#plugins4_q').val();
                $('#lazy').jstree(true).search(v);
            }, 250);
        });

        var allKeys = consulUrl + "?keys";

        getTree(tree, true, false);

        $('#lazy').on("select_node.jstree", function (e, data) {
            if (data.node.id.substr(-1) != '/') {
                getValue(data.node.id, $('#cKeyValue'));
            }
            $('#selectedNodeID').text(data.node.id);
        });
    });

</script>
</body>
</html>
