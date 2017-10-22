$(function () {
    var renameModalObj = $('#renameModalId'),
        createNodeModalObj = $('#createNodeModalId');

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
        localStorage.removeItem('createFolderCallee');
    });

    createNodeModalObj.on('shown.bs.modal', function () {
        var selectedNodePath = $('#selectedNodeID').text(),
            splitUpArray, newPath;
        $('#keyInputId').focus();

        if (localStorage['createFolderCallee'] === "contextMenu") {
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
        if (localStorage['treeBackup']) {
            localStorage['jstree'] = localStorage['treeBackup'];
            localStorage.removeItem('treeBackup');
        }
        location.reload();
    });

    $('#importConsulBtnId').on('click', function () {
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
        localStorage['createFolderCallee'] = 'createRootBtnId';
    });

    $("#searchClear").on('click', function () {
        var searchInputObj = $("#searchInputId");
        searchInputObj.val('');
        checkClearIcon();
        searchInputObj.trigger('keyup');
        searchInputObj.focus();
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
        if (localStorage['overwriteFor'] === "import") {
            importConsul($(this).data('answer'));
        } else if (localStorage['overwriteFor'] === "ccp") {
            ccPaste($(this).data('answer'));
        }
    });
});