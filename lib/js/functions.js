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

function getFromLocalStorage(key){
    var localStorageMainKeyLabel = window.location.host + "-" + document.location.pathname.split("/").slice(-2, -1).toString(),
        localStorageElem = JSON.parse(localStorage[localStorageMainKeyLabel]);

    if (key){
        return localStorageElem[key];
    } else {
        return localStorageElem;
    }
}

function convertMultipleSelectionTo1(){
    if (localStorage['jstree']) {
        var jsTressStorageElem = JSON.parse(localStorage['jstree']), cleanTreeData;
        if (jsTressStorageElem.state.core.selected.length > 0){
            var selectedItemsFromPreviousSession = jsTressStorageElem.state.core.selected;
            jsTressStorageElem.state.core.selected = [selectedItemsFromPreviousSession.slice(-1).pop()];
            cleanTreeData = JSON.stringify(jsTressStorageElem);
            localStorage['jstree'] = cleanTreeData;
        }
    }
}

function setToLocalStorage(key, value) {
    var localStorageMainKeyLabel = window.location.host + "-" + document.location.pathname.split("/").slice(-2, -1).toString(),
        localStorageElem = {};

    if(localStorage[localStorageMainKeyLabel]){
        localStorageElem = getFromLocalStorage();
    }

    localStorageElem[key] = value;
    localStorage[localStorageMainKeyLabel] = JSON.stringify(localStorageElem);
}

function removeFromLocalStorage(key){
    var localStorageMainKeyLabel = window.location.host + "-" + document.location.pathname.split("/").slice(-2, -1).toString(),
        localStorageElem = getFromLocalStorage();

    if (key){
        delete localStorageElem[key];
        delete localStorage[localStorageMainKeyLabel];
        $.each(localStorageElem, function (key, value) {
            setToLocalStorage(key, value);
        });
    } else {
        delete localStorage[localStorageMainKeyLabel];
    }
}

function getSetConfig(modify) {
    var consulUrlSelector = $("#consulUrlSelectorId"),
        selectedConsulIdx = consulUrlSelector.find(":selected").attr("data-idx"),
        consul = getFromLocalStorage('consulConfig'),
        stringedObj = {
            "url": consul[selectedConsulIdx].url,
            "title": consul[selectedConsulIdx].title
        };

    if (getFromLocalStorage('selectedConsul')) {
        if (modify) {
            setToLocalStorage('selectedConsul', stringedObj);
            location.reload();
        }
    } else {
        setToLocalStorage('selectedConsul', stringedObj);
    }

    var tobeReturned =  getFromLocalStorage('selectedConsul');
    consulUrlSelector.val(tobeReturned.url).attr("selected", "selected");
    setToLocalStorage('consulUrl', tobeReturned.url);

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
    $('#consulUrlId').text(extractHostname(getFromLocalStorage('consulUrl')));
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
    removeFromLocalStorage();
    location.reload();
}

function importConsul(cas) {
    $('#overwriteModalId').modal('hide');
    var files = document.getElementById('jsonInputFile').files,
        consulUrl = getFromLocalStorage('consulUrl'),
        fr = new FileReader();
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };
    fr.onload = function (e) {
        var importedFileC = JSON.parse(e.target.result),
            importedDataLen = Object.size(importedFileC),
            counter = 0, encodedValue, obj={};

        $('#dataToBeImportedCount').text(importedDataLen);
        $('#importExportModalId').modal('hide');
        $('#processingMdlID').modal({
            backdrop: 'static',
            keyboard: false
        });
        $.each(importedFileC, function (key, value) {
            encodedValue = window.btoa(value);
            $.ajax({
                method: "POST",
                url: "backend/requests.php",
                data: {
                    consul: consulUrl,
                    path: key,
                    method: "IMPORT",
                    cas: cas,
                    value: encodedValue
                }
            }).done(function (res) {
                counter++;
                var result = JSON.parse(res),fieldClass,
                    key = result['key'], markup,
                    status = result['value'];

                if (status === "OK"){
                    fieldClass = "bg-success";
                } else {
                    fieldClass = "bg-danger";
                }

                markup = "<tr><td colspan=\"3\">" + key + "</td><td class=\"" + fieldClass + "\" colspan=\"2\">" + status + "</td></tr>";
                $("#summaryTableId tbody").prepend(markup);
            });
            $('#processingMdlID').modal('hide');
        });
    };
    fr.readAsText(files.item(0));
    $('#importSummaryModalId').modal('show');
}

function enableExportMode(){
    setToLocalStorage('treeBackup', getFromLocalStorage('jstree'));
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
    if (getFromLocalStorage('searchTimeout')) {
        clearTimeout(getFromLocalStorage('searchTimeout'));
        removeFromLocalStorage('searchTimeout');
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
            consul: getFromLocalStorage('consulUrl'),
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
    var consul = getFromLocalStorage('consulConfig'),
        optionElements = '';

    $.each(consul, function (key) {
        optionElements += '<option value="' + consul[key].url + '" data-idx="' + [key] + '">' + extractHostname(consul[key].url) + '</option>';
    });
    $("#consulUrlSelectorId").html(optionElements);
}

function renDupNode() {
    var newNodeName = $('#newNodePathId').val(),
        renDupObj = getFromLocalStorage('workingObj'),
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
                consul: getFromLocalStorage('consulUrl'),
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
            method: "TREE",
            consul: allKeys
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
    path = getFromLocalStorage('consulUrl') + path + "?raw";
    $('#valueContentLengthId').text('0');
    $.ajax({
        method: "GET",
        url: "backend/requests.php",
        data: {
            method: "VALUE",
            consul: path
        }
    }).done(function (data) {
        var realData = JSON.parse(data);
        if (obj) {
            obj.text(window.atob(realData['data']));
            obj.val(window.atob(realData['data']));
            $('#valueContentLengthId').text(realData['data'].length);
        } else {
            $('#gotNodeValue').text(realData['data']);
        }
        updateFieldsToggle(false, $('#userRights').text());
    });
}

function ccPaste(cas) {
    var parentId = getFromLocalStorage('ccpObjParent'),
        replace = getFromLocalStorage('ccpObjId'),
        path = getFromLocalStorage('ccpObjPaths'),
        ccType = getFromLocalStorage('ccpObjType'),
        srcConsul = getFromLocalStorage('ccpObjConsul');

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
            consul: getFromLocalStorage('consulUrl'),
            parentId: parentId,
            replace: replace,
            ccType: ccType,
            srcConsul: srcConsul,
            path: path,
            cas: cas
        }
    }).done(function () {
        removeFromLocalStorage('ccpObjParent');
        removeFromLocalStorage('ccpObjId');
        removeFromLocalStorage('ccpObjType');
        removeFromLocalStorage('ccpSourcePaths');
        removeFromLocalStorage('ccpObjPaths');
        removeFromLocalStorage('ccpObjConsul');
        removeFromLocalStorage('overwriteFor');
        location.reload();
    })
}

function sendToConsul(path, value, reload, updateBtn) {
    var userRights = $('#userRights').text(),
        fullPath, encodedValue;

    path = path.replace(/\\/g, '/');

    if (updateBtn) {
        updateFieldsToggle(true, userRights);
    }

    if (path[0] === '/') {
        path = path.substring(1);
    }

    fullPath = getFromLocalStorage('consulUrl') + path;
    encodedValue = window.btoa(value);

    $.ajax({
        method: "POST",
        url: "backend/requests.php",
        data: {
            method: "PUT",
            path: fullPath,
            cas: 1,
            value: encodedValue
        }
    }).done(function () {
        if (reload) {
            if (updateBtn) {
                updateFieldsToggle(false, userRights);
                getValue(path, $('#cKeyValue'));
            } else {
                location.reload();
            }
        }
    })
}

function deleteNode(path) {
    $('#processingMdlID').modal({
        backdrop: 'static',
        keyboard: false
    });
    setTimeout(function () {
        $.ajax({
            method: "POST",
            url: "backend/requests.php",
            data: {
                method: "DELETE",
                consul: getFromLocalStorage('consulUrl'),
                path: path
            }
        }).done(function () {
            location.reload();
        })
    }, 250);
}

function updateProgress(percent){
    var progressModal = $('#processingMdlID').find('.progress-bar');
    progressModal.attr('aria-valuenow', percent);
    progressModal.css('width', percent + "%");
    progressModal.text(percent + "%");
}

function exportConsul(data) {
    $('#exportModalId').modal('hide');
    $('#processingMdlID').modal({
        backdrop: 'static',
        keyboard: false
    });

    var consulSplit = getFromLocalStorage('consulUrl').split("/");
    var consulAddress = consulSplit[0] + "//" + consulSplit[2];

    $.ajax({
        method: "POST",
        url: "backend/requests.php",
        dataType: "json",
        data: {
            consul: consulAddress,
            method: "EXPORT",
            path: JSON.stringify(data)
        }
    }).done(function (data) {
        updateProgress(100);
        pushToDownload(data);
    });
}

function pushToDownload(data){
    var exportFileNameObj = $('#exportFileName'),filename,
        selectedConsul = getFromLocalStorage('selectedConsul'),
        consulTitle = selectedConsul['title'];

    if (exportFileNameObj.val().trim().length === 0){
        filename = consulTitle.toLowerCase().replace(' ', '-') + '-exported.json';
    } else {
        filename = exportFileNameObj.val() + '.json';
    }

    if (data.length !== 0) {
        var contentType = 'text/json';
        var jsonFile = new Blob([JSON.stringify(data)], {type: contentType});
        var a = document.createElement('a');
        a.download = filename;
        a.href = window.URL.createObjectURL(jsonFile);
        a.textContent = 'Download Consul-Tree-data';
        a.dataset.downloadurl = [contentType, a.download, a.href].join(':');
        document.body.appendChild(a);
        a.click();
        a.remove();
    }
    $('#processingMdlID').modal('hide');
}

function parseCustomJson(data, tree, file) {
    var xdata, name, parent, xname, filename;
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

        xdata = data;
        xdata.splice(xdata.indexOf(picked), 1);

        for (i = 0; i < xdata.length; i++) {
            name = xdata[i];
            parent = "";
            if (name.substr(name.length - 1, 1) === '/') {
                xname = name.substr(0, name.length - 1);
                parent = xname.substr(0, xname.lastIndexOf("/") + 1)
            } else {
                parent = name.substr(0, name.lastIndexOf("/") + 1)
            }

            filename = name.match(/([^\/]*)\/*$/)[1];

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
        filteredSplitPath, stringLen, list;

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
        stringLen = roots[i].length;
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
    var consulUrl = getFromLocalStorage('consulUrl'),
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

                setToLocalStorage('createFolderCallee', 'contextMenu');
                $('#pathDescribeID').text(obj.id);
                $('#createNodeModalId').modal('show');
            }
        },
        "rename": {
            "separator_before": false,
            "icon": false,
            "separator_after": false,
            "_disabled": function () {
                return userRights.charAt(1) !== "1";
            },
            "label": "Rename",
            "action": function (data) {
                var inst = $.jstree.reference(data.reference),
                    obj = inst.get_node(data.reference);

                setToLocalStorage('workingObj', obj);
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

                setToLocalStorage('workingObj', obj);
                renameBtnObj.text('Duplicate');
                renameBtnObj.attr('data-type', 'duplicate');
                renameModalObj.find('h4 strong').text('Duplicate Node');
                OldNodeObj.val(obj['text']);
                renameModalObj.modal('show');
            }
        },
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

                setToLocalStorage('ccpObjPaths', obj['id']);
                setToLocalStorage('ccpObjParent', obj.parent);
                setToLocalStorage('ccpObjType', 'cut');
                setToLocalStorage('ccpObjConsul', consulUrl);
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

                setToLocalStorage('ccpObjPaths', obj['id']);
                setToLocalStorage('ccpObjParent', obj.parent);
                setToLocalStorage('ccpObjType', 'copy');
                setToLocalStorage('ccpObjConsul', consulUrl);
            }
        },
        "paste": {
            "separator_before": false,
            "icon": false,
            "_disabled": function () {
                if (userRights.charAt(1) !== "1") {
                    return true;
                } else {
                    return !(getFromLocalStorage('ccpObjPaths') && getFromLocalStorage('ccpObjPaths').length > 0);
                }
            },
            "separator_after": false,
            "label": "Paste",
            "action": function (data) {
                var inst = $.jstree.reference(data.reference),
                    obj = inst.get_node(data.reference);

                setToLocalStorage('ccpObjId', obj.id);
                setToLocalStorage('overwriteFor', "ccp");
                $('#overwriteModalId').modal('show');
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

                $('#toBeDeletedNodeId').text(obj['id']);
                $('#deleteModalId').modal('show');
            }
        }
    };
    return items;
}
