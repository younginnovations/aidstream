$(document).ready(function () {
    var status;

    if (!importing) {
        var timer = setInterval(function () {
            $.ajax({
                url: checkSessionRoute,
                method: 'GET'
            }).success(function (response) {
                var placeHolder = $('div#import-status-placeholder');

                if (response.status == 'Complete') {
                    placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing " + response.status + " <small>(Please click here to view the processed Activities.)</small>" + "</a>");
                    status = 'Complete';
                } else if (response.status == 'Processing') {
                    placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File " + response.status + " <small>(Please click here to view the processed Activities.)</small>" + "</a>");
                }
            });

            if (status == 'Complete' || status == null) {
                clearInterval(timer);
            }
        }, 1000);
    } else {
        var placeHolder = $('div#import-status-placeholder');

        placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing Complete.  " + "<small>(Please click here to view the processed Activities.)</small>" + "</a>");
    }
});
