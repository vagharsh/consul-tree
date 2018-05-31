$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#pageTitle').text("Consul Tree | " + window.location.hostname);

    convertMultipleSelectionTo1();

    $.getJSON("config/config.json", function (consul) {
        if (consul) if (consul.length !== 0) {
            var selectedConsulJson, tree;
            setToLocalStorage('consulConfig', consul);
            getConsulLocations();
            checkRights(userRights);
            selectedConsulJson = getSetConfig();
            tree = {
                'contextmenu': {'items': customMenu},
                'check_callback': true,
                'plugins': ['contextmenu', 'types', 'state', 'search'],
                'core': {
                    "multiple": false,
                    "animation": 0,
                    "check_callback": true,
                    "themes": {
                        'name': 'default',
                        'responsive': true,
                        "stripes": true
                    },
                    'data': []
                }
            };

            if (getFromLocalStorage('treeBackup')) {
                setToLocalStorage('jstree', getFromLocalStorage('treeBackup'));
                removeFromLocalStorage('treeBackup');
            }

            getTree(tree, selectedConsulJson.url + "?keys");
        }
    });
});