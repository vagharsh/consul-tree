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

function noDataReturn() {
    $('#noTreeModalId').modal({
        backdrop: 'static',
        keyboard: false
    });
    var createRootBtnObj = $('#createRootBtnId');
    $('#searchInputId').attr('disabled', true);
    $('#enableExportBtnId').attr('disabled', true);
    createRootBtnObj.removeClass('hidden');
    $('#importExportBtnId').attr('disabled', false);
    createRootBtnObj.attr('disabled', false);
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

function getSetConfig(modify) {
    var consulUrlSelector = $("#consulUrlSelectorId"),
        selectedConsulIdx = consulUrlSelector.find(":selected").attr("data-idx"),
        consul = JSON.parse(localStorage['consulConfig']),
        SelectedConsul,
        stringedObj = {
            "url": consul[selectedConsulIdx].url,
            "title": consul[selectedConsulIdx].title
        };

    if (localStorage['selectedConsul']) {
        if (modify) {
            SelectedConsul = JSON.stringify(stringedObj);
            localStorage['selectedConsul'] = SelectedConsul;
            location.reload();
        }
    } else {
        SelectedConsul = JSON.stringify(stringedObj);
        localStorage['selectedConsul'] = SelectedConsul;
    }

    var tobeReturned =  JSON.parse(localStorage['selectedConsul']);
    consulUrlSelector.val(tobeReturned.url).attr("selected", "selected");
    localStorage['consulUrl'] = tobeReturned.url;

    $('#consulFullUrlId').text(tobeReturned.url);
    $('#consulTitleID').text(tobeReturned.title);
    return tobeReturned;
}

function checkRights(rights) {
    var readACL = rights.charAt(0),
        writeACL = rights.charAt(1);

    $('#userRights').text(rights);

    if (readACL !== "1") {
        $('.readACL').remove();
    } else if (writeACL !== "1") {
        $('.writeACL').remove();
    }
}

function no200return() {
    $('#consulUrlId').text(extractHostname(localStorage['consulUrl']));
    $('#noConnectionModalId').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#searchInputId').attr('disabled', true);
    $('#enableExportBtnId').attr('disabled', true);
    $('#importExportBtnId').attr('disabled', true);
    $('#createRootBtnId').attr('disabled', true);
    $('#disableManualExport').attr('disabled', true);
    $('#exportSelection').attr('disabled', true);
}

function resetLocationStorage() {
    localStorage.clear();
    location.reload();
}

function importConsul(cas) {
    $('#overwriteModalId').modal('hide');
    var files = document.getElementById('jsonInputFile').files,
        consulUrl = localStorage['consulUrl'],
        fr = new FileReader();

    fr.onload = function (e) {
        var result = JSON.parse(e.target.result);
        $('#importExportModalId').modal('hide');
        $('#processingMdlID').modal({
            backdrop: 'static',
            keyboard: false
        });
        $.ajax({
            method: "POST",
            url: "backend/requests.php",
            data: {
                path: consulUrl,
                method: "BulkIMPORT",
                cas: cas,
                value: JSON.stringify(result)
            }
        }).done(function () {
            localStorage.removeItem('overwriteFor');
            location.reload();
        });
    };
    fr.readAsText(files.item(0));
}

function enableExportMode(){
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
}

function updateFieldsToggle(readonly) {
    var cKeyValueObj = $('#cKeyValue'),
        updateBtnId = $('#valueUpdateBtnId'),
        userRights = $('#userRights').text();

    if (!readonly) {
        cKeyValueObj.parent().parent().parent().removeClass('panel-warning');
        cKeyValueObj.parent().parent().parent().addClass('panel-success');
        if (userRights.charAt(1) === "1") {
            cKeyValueObj.attr('readonly', false);
        }
        updateBtnId.removeClass('disabled');
        updateBtnId.attr('disabled', false);
    } else {
        cKeyValueObj.parent().parent().parent().removeClass('panel-default');
        cKeyValueObj.parent().parent().parent().addClass('panel-warning');
        cKeyValueObj.attr('readonly', true);
        updateBtnId.addClass('disabled');
        updateBtnId.attr('disabled', true);
    }
}

function checkClearIcon() {
    var searchClearIcon = $('#searchClear');
    if ($('#searchInputId').val().length > 0) {
        searchClearIcon.removeClass('glyphicon-search');
        searchClearIcon.addClass('glyphicon-remove');
    } else {
        searchClearIcon.removeClass('glyphicon-remove');
        searchClearIcon.addClass('glyphicon-search');
    }
    if (localStorage['searchTimeout']) {
        clearTimeout(localStorage['searchTimeout']);
        localStorage.removeItem('searchTimeout');
    }
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
    var tobeFixedData = [], i, onlyFolders,
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
        if (data.indexOf(uniqueFolders[i]) === -1) {
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
        url: "backend/requests.php",
        data: {
            method: "FIX",
            consul: localStorage['consulUrl'],
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

function getConsulLocations(){
    var consul = JSON.parse(localStorage['consulConfig']),
        optionElements = '';

    $.each(consul, function (key) {
        optionElements += '<option value="' + consul[key].url + '" data-idx="' + [key] + '">' + extractHostname(consul[key].url) + '</option>';
    });
    $("#consulUrlSelectorId").html(optionElements);
}

function renDupNode() {
    var newNodeName = $('#newNodePathId').val(),
        renDupObj = JSON.parse(localStorage['workingObj']),
        objChildren = renDupObj['children_d'],
        objParent = renDupObj['parent'],
        oldObjPath = renDupObj['id'],
        newNodePath, newNodeString, newData = {},
        renDupType = $('#renameConfirmBtnId').data('type');

    $('#renameModalId').modal('hide');
    $('#processingMdlID').modal({
        backdrop: 'static',
        keyboard: false
    });

    if (objParent === '#') objParent = '';

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
            url: "backend/requests.php",
            data: {
                consul: localStorage['consulUrl'],
                method: renDupType.toUpperCase(),
                path: JSON.stringify(newData),
                selectedObj: oldObjPath
            }
        }).done(function () {
            location.reload();
        });
    }, 250);
}

function getTree(tree, allKeys) {
    //console.log("Establishing Connection to the Consul host");
    $('#connectingModalId').modal({
        backdrop: 'static',
        keyboard: false
    });

    $.ajax({
        method: "GET",
        url: "backend/requests.php",
        dataType: 'json',
        data: {
            path: allKeys
        }
    }).done(function (data) {
        $('#connectingModalId').modal('hide');
        if (data['data'] === '[]') {
            //console.log("No Data was found on Consul");
            noDataReturn();
        } else if (data['http_code'] !== 200) {
            //console.log("No Connection to the Consul host");
            no200return();
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
                    if (obj.length === 1) {
                        if (obj[0].indexOf("/") === -1) {
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

function getValue(path, obj) {
    path = localStorage['consulUrl'] + path + "?raw";

    $.ajax({
        method: "GET",
        url: "backend/requests.php",
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
        updateFieldsToggle(false, $('#userRights').text());
    });
}

function ccPaste(cas) {
    var parentId = localStorage['ccpObjParent'],
        replace = localStorage['ccpObjId'],
        path = localStorage['ccpObjPaths'],
        ccType = localStorage['ccpObjType'],
        srcConsul = localStorage['ccpObjConsul'];

    $('#overwriteModalId').modal('hide');
    $('#processingMdlID').modal({
        backdrop: 'static',
        keyboard: false
    });

    if (parentId === '#') parentId = '';

    $.ajax({
        method: "POST",
        url: "backend/requests.php",
        data: {
            method: "CCP",
            consul: localStorage['consulUrl'],
            parentId: parentId,
            replace: replace,
            ccType: ccType,
            srcConsul: srcConsul,
            path: path,
            cas: cas
        }
    }).done(function () {
        localStorage.removeItem('ccpObjParent');
        localStorage.removeItem('ccpObjId');
        localStorage.removeItem('ccpObjType');
        localStorage.removeItem('ccpSourcePaths');
        localStorage.removeItem('ccpObjPaths');
        localStorage.removeItem('ccpObjConsul');
        localStorage.removeItem('overwriteFor');
        location.reload();
    })
}

function sendToConsul(path, value, reload, updateBtn) {
    var userRights = $('#userRights').text();

    path = path.replace(/\\/g, '/');

    if (updateBtn) {
        updateFieldsToggle(true, userRights);
    }

    if (path[0] === '/') {
        path = path.substring(1);
    }
    var fullPath = localStorage['consulUrl'] + path;
    $.ajax({
        method: "POST",
        url: "backend/requests.php",
        data: {
            method: "PUT",
            path: fullPath,
            value: value
        }
    }).done(function () {
        if (reload) {
            if (updateBtn) {
                updateFieldsToggle(false, userRights);
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
        url: "backend/requests.php",
        data: {
            method: "DELETE",
            consul: localStorage['consulUrl'],
            path: path
        }
    }).done(function () {
        location.reload();
    })
}

function exportConsul(data) {
    var selectedConsul = JSON.parse(localStorage['selectedConsul']),
        consulTitle = selectedConsul['title'],
        newData = JSON.stringify(data);

    $('#processingMdlID').modal({
        backdrop: 'static',
        keyboard: false
    });
    $.ajax({
        method: "POST",
        url: "backend/requests.php",
        dataType: "json",
        data: {
            consul: localStorage['consulUrl'],
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
        var minLen = -1,
            picked = "", i;
        for (i = 0; i < data.length; i++) {
            if (data[i].length < minLen || minLen === -1) {
                minLen = data[i].length;
                picked = data[i];
            }
        }

        tree.core.data.push({"id": picked, "parent": "#", "text": picked.slice(0, -1)});
        var xdata = data;
        xdata.splice(xdata.indexOf(picked), 1);

        for (i = 0; i < xdata.length; i++) {
            var name = xdata[i];
            var parent = "";
            if (name.substr(name.length - 1, 1) === '/') {
                var xname = name.substr(0, name.length - 1);
                parent = xname.substr(0, xname.lastIndexOf("/") + 1)
            } else {
                parent = name.substr(0, name.lastIndexOf("/") + 1)
            }

            var filename = name.match(/([^\/]*)\/*$/)[1];

            if (name.substr(-1) === '/') {
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
        filteredSplitPath;

    $.each(data, function (key, item) {
        filteredSplitPath = item.split("/");
        if (item.indexOf("/") === -1) {
            roots.push(item);
        }
        if (filteredSplitPath.length !== 1) {
            if (filteredSplitPath[1].length === 0) {
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

function customMenu() {
    var consulUrl = localStorage['consulUrl'],
        renameBtnObj = $('#renameConfirmBtnId'),
        renameModalObj = $('#renameModalId'),
        userRights = $('#userRights').text(),
        OldNodeObj = $('#oldNodePathId'),items;

    items = {
        "create": {
            "separator_before": false,
            "separator_after": true,
            "_disabled": function () {
                return userRights.charAt(1) !== "1";
            },
            "label": "Create",
            "action": function (data) {
                var inst = $.jstree.reference(data.reference),
                    obj = inst.get_node(data.reference);

                localStorage['createFolderCallee'] = 'contextMenu';
                $('#pathDescribeID').text(obj.id);
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
                var inst = $.jstree.reference(data.reference),
                    obj = inst.get_node(data.reference);

                localStorage['workingObj'] = JSON.stringify(obj);
                renameBtnObj.text('Rename');
                renameBtnObj.attr('data-type', 'rename');
                renameModalObj.find('h4 strong').text('Rename Node');
                OldNodeObj.val(obj['text']);
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
                var inst = $.jstree.reference(data.reference),
                    obj = inst.get_node(data.reference);

                localStorage['workingObj'] = JSON.stringify(obj);
                renameBtnObj.text('Duplicate');
                renameBtnObj.attr('data-type', 'duplicate');
                renameModalObj.find('h4 strong').text('Duplicate Node');
                OldNodeObj.val(obj['text']);
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
                        if (userRights.charAt(1) !== "1") {
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
                        deleteNode(obj['id']);
                    }, 250);
                }
            }
        }
    };
    return items;
}
