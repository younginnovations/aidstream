$(document).ready(function () {
    var status;
    var localisedData;

    var checkTransactionSessionStatus = function () {
        setTimeout(function () {
            $.ajax({
                url: checkSessionRoute,
                method: 'GET'
            }).success(function (response) {
                var placeHolder = $('div#import-status-placeholder');
                var link = '/activity/' + activity + '/import-transaction/status';

                if (response.status == null) {
                    return;
                }

                if (response.status == 'Completed') {
                    placeHolder.empty().append("<a href=" + link + ">" + localisedData['csv_file_processing_completed'] + "</a>");

                    return;
                }

                if (response.status == 'Processing') {
                    placeHolder.empty().append("<a href=" + link + ">" + localisedData['csv_file_processing'] + "</a>");

                    checkTransactionSessionStatus();
                }

                if (response.status == 'Error') {
                    placeHolder.empty().append(localisedData['error_processing_csv']);
                }
            });
        }, 2000);
    };

    var callAsync = function (url, methodType) {
        return $.ajax({
            url: url,
            type: methodType
        });
    }

    callAsync('/import-activity/localisedText', 'get').success(function (data) {
        localisedData = JSON.parse(data);
        checkTransactionSessionStatus();
    });


});

