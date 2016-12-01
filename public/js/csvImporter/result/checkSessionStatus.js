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

                if (response.status == 'Complete') {
                    placeHolder.empty().append("<a href='/activity/'" + activity + "'/import-activity/import-status'>" + "Csv File Processing " + response.status + "</a>");

                    return;
                }

                if (response.status == 'Processing') {
                    placeHolder.empty().append("<a href='/activity/'" + activity + "'/import-activity/import-status'>" + "Csv File " + response.status + "</a>");

                    checkResultSessionStatus();
                }
            });
        }, 3000);
    };

    checkResultSessionStatus();
});
