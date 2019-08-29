$(document).ready(function () {
    var status;

    var checkResultSessionStatus = function () {
        setTimeout(function () {
            $.ajax({
                url: checkSessionRoute,
                method: 'GET'
            }).success(function (response) {
                var placeHolder = $('div#import-status-placeholder');

                if (response.status == null) {
                    return;
                }
                if (response.status == 'Error') {
                    placeHolder.empty().append($('<a/>', {
                        href: '/activity/' + activity + '/import-result/import-status',
                        text: "Csv File Processing " + response.status
                    }));

                    return;
                }

                if (response.status == 'Complete') {
                    placeHolder.empty().append($('<a/>', {
                        href: '/activity/' + activity + '/import-result/import-status',
                        text: "Csv File Processing " + response.status
                    }));

                    return;
                }

                if (response.status == 'Processing') {
                    placeHolder.empty().append($('<a/>', {
                        href: '/activity/' + activity + '/import-result/import-status',
                        text: "Csv File " + response.status
                    }));

                    checkResultSessionStatus();
                }
            });
        }, 3000);
    };

    checkResultSessionStatus();
});
