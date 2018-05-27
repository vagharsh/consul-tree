$(function () {
    var renameModalObj = $('#renameModalId'),
        createNodeModalObj = $('#createNodeModalId'),
        consulTreeDivID = $('#ConsulTree');

    $('#newNodePathId').on('keyup', function () {
        var newText = $('#newNodePathId').val();
        if (newText.length >= 1) {
            $('#renameConfirmBtnId').attr('disabled', false);
        } else {
            $('#renameConfirmBtnId').attr('disabled', true);
        }
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
        removeFromLocalStorage('createFolderCallee');
    });

    createNodeModalObj.on('shown.bs.modal', function () {
        var selectedNodePath = $('#selectedNodeID').text(),
            splitUpArray, newPath;
        $('#keyInputId').focus();

        if (getFromLocalStorage('createFolderCallee') === "contextMenu") {
            if (selectedNodePath.substr(selectedNodePath.length - 1) !== '/') {
                splitUpArray = selectedNodePath.split("/");
                splitUpArray.splice(splitUpArray.length - 1, 1);
                newPath = splitUpArray.join('/');
                selectedNodePath = newPath + '/';
            }
        } else {
            selectedNodePath = '/';
        }

        $('#pathInputId').val(selectedNodePath);
        $('#pathDescribeID').text(selectedNodePath);

        check4Key();
    });

    $('#resetLocationBtnId').on('click', resetLocationStorage);

    $('#disableManualExport').on('click', function () {
        if (getFromLocalStorage('treeBackup')) {
            setToLocalStorage('jstree', getFromLocalStorage('treeBackup'));
            removeFromLocalStorage('treeBackup');
        }
        location.reload();
    });

    $('#importConsulBtnId').on('click', function () {
        var files = document.getElementById('jsonInputFile').files;
        if (files.length <= 0) {
            alert('JSON file must be selected first.');
            return false;
        }
        setToLocalStorage('overwriteFor', "import");
        $('#overwriteModalId').modal('show');
    });

    $('#createRootBtnId').on('click', function () {
        $('#createNodeModalId').modal('show');
        setToLocalStorage('createFolderCallee', 'createRootBtnId');
    });

    $("#searchClear").on('click', function () {
        var searchInputObj = $("#searchInputId");
        searchInputObj.val('');
        checkClearIcon();
        searchInputObj.trigger('keyup');
        searchInputObj.focus();
        removeFromLocalStorage('searchTimeout');
    });

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

    $('#renameConfirmBtnId').on('click', renDupNode);

    $('.overwriteBtn').on('click', function () {
        if (getFromLocalStorage('overwriteFor') === "import") {
            importConsul($(this).data('answer'));
        } else if (getFromLocalStorage('overwriteFor') === "ccp") {
            ccPaste($(this).data('answer'));
        }
    });

    $('#createKeyBtnId').on('click', function () {
        var nodeName = $('#pathDescribeID').val(),
            nodeValue = $('#inputKeyValueId').val(),
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
                    sendToConsul(nodeName, nodeValue, true, false);
                } else {
                    sendToConsul(toBeCreatedPath, nodeValue, true, false);
                }
            } else {
                sendToConsul(toBeCreatedPath, nodeValue, false, false);
            }
        }
    });

    $('#importSummaryModalId').on('hidden.bs.modal', function () {
        location.reload();
    });

    $('#valueUpdateBtnId').on('click', function () {
        var path = $('#selectedNodeID').text(),
            cKeyValueObj = $('#cKeyValue'),
            value = cKeyValueObj.val();

        cKeyValueObj.val('Loading...');
        sendToConsul(path, value, true, true)
    });

    consulTreeDivID.on("deselect_node.jstree", function () {
        var updateControl = $('.update-control'),
            cKeyValueObj = $('#cKeyValue');

        updateControl.addClass('hidden');
        cKeyValueObj.parent().parent().prev().text('No item is selected to view it\'s value.');
        cKeyValueObj.parent().parent().parent().removeClass('panel-warning');
        cKeyValueObj.parent().parent().parent().removeClass('panel-success');
        cKeyValueObj.parent().parent().parent().addClass('panel-default');
        cKeyValueObj.val('');
        $('#createElementText').removeClass('hidden');
    });

    consulTreeDivID.on("select_node.jstree", function (e, data) {
        var updateControl = $('.update-control'),
            cKeyValueObj = $('#cKeyValue'),
            selectedNodeText = data.node.id;

        cKeyValueObj.parent().parent().prev().text(selectedNodeText);
        $('#selectedNodeID').text(selectedNodeText);
        cKeyValueObj.val('Loading...');
        updateFieldsToggle(true);
        if (data.node.id.substr(-1) !== '/') {
            updateControl.removeClass('hidden');
            $('#createElementText').addClass('hidden');
            getValue(data.node.id, cKeyValueObj);
        } else {
            updateControl.addClass('hidden');
            $('#createElementText').removeClass('hidden');
            cKeyValueObj.val('');
            cKeyValueObj.parent().parent().parent().removeClass('panel-warning');
            cKeyValueObj.parent().parent().parent().removeClass('panel-success');
            cKeyValueObj.parent().parent().parent().addClass('panel-default');
        }
    });

    $('#enableExportBtnId').on('click', function () {
        var allKeys = getFromLocalStorage('consulUrl') + "?keys";
        enableExportMode();
        var tree = {
            'contextmenu': {'items': customMenu},
            "plugins": ["checkbox", "types", "state", "search"],
            'core': {
                "multiple": true,
                "animation": 0,
                "check_callback": true,
                "themes": {"stripes": true},
                'data': []
            }
        };

        getTree(tree, allKeys);
    });

    $("#consulUrlSelectorId").on('change', function () {
        getSetConfig(true);
    });

    $('#exportSelection').on('click', function () {
        $('#exportFileName').val('');
        $('#exportModalId').modal('show');
    });

    $('#exportModalId').on('shown.bs.modal', function () {
        $('#exportFileName').focus();
    });

    $('#exportConfirmBtnId').on('click', function (){
        exportConsul(consulTreeDivID.jstree(true).get_selected());
    });

    $('#searchInputId').on('keyup', function () {
        var consulTreeObj = $('#ConsulTree');
        var searchInputObj = $('#searchInputId');
        checkClearIcon();
        setToLocalStorage('searchTimeout', setTimeout(function () {
            consulTreeObj.jstree(true).search(searchInputObj.val());
            var searchCount = consulTreeObj.find('.jstree-search').length;
            if (searchCount > 0){
                searchInputObj.parent().addClass('has-success');
            } else {
                searchInputObj.parent().addClass('has-error ');
            }
        }, 150));

        setTimeout(function () {
            searchInputObj.parent().removeClass('has-success');
            searchInputObj.parent().removeClass('has-error');
        }, 1000);
    });

    $('#deleteConfirmBtnId').on('click', function () {
        $('#deleteModalId').modal('hide');
        deleteNode($('#toBeDeletedNodeId').text());
    });
});