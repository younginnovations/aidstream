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

                placeHolder.empty().append($('<a/>', {
                    href: '/activity/' + activity + '/import-result/import-status',
                    text: "Csv File Processing " + response.status
                }));

                if (response.status == 'Complete') {
                    return;
                }

                if (response.status == 'Processing') {
                    checkResultSessionStatus();
                }
            });
        }, 3000);
    };

    checkResultSessionStatus();
});
