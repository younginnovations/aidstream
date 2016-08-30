$(document).ready(function () {
    var status;

    var checkSessionStatus = function () {
        setTimeout(function () {
            $.ajax({
                    url: checkSessionRoute,
                method: 'GET'
        }).success(function (response) {
                var placeHolder = $('div#import-status-placeholder');

                if (response.status == null) {
                    return;
                }

                if (response.status == 'Complete') {
                    placeHolder.empty().append("<a href='/import-activity/import-status'>" + "Csv File Processing " + response.status + "</a>");

                    return;
                }

                if (response.status == 'Processing') {
                    placeHolder.empty().append("<a href='/import-activity/import-status'>" + "Csv File " + response.status + "</a>");

                    checkSessionStatus();
                }
            });
        }, 3000);
    };

    checkSessionStatus();
});
