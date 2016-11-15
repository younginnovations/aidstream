var submitButton = $('input#submit-valid-activities');
var cancelButton = $('input#cancel-import');
var checkAll = $('div#checkAll');
var count = 0;
var counter = $('input#value');
var transferComplete = false;
var clearInvalidButton = $('#clear-invalid');
var validParentDiv = $('.valid-data');
var invalidParentDiv = $('.invalid-data');
var allDataDiv = $('.all-data');

var CsvImportStatusManager = {
    getParentDiv: function (selector) {
        return $('div.' + selector);
    },
    enableImport: function () {
        submitButton.fadeIn("slow").removeClass('hidden');
    },
    callAsync: function (url, methodType) {
        return $.ajax({
            url: url,
            type: methodType
        });
    },
    ifParentIsEmpty: function (className) {
        var parentDiv = CsvImportStatusManager.getParentDiv(className);

        return parentDiv.is(':empty');
    },
    isTransferComplete: function () {
        var placeHolder = $('div#import-status-placeholder');
        placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing." + "</a>");

        CsvImportStatusManager.callAsync('/import-activity/check-status', 'GET').success(function (response) {
            var r = JSON.parse(response);

            if (r.status == 'Error') {
                cancelButton.fadeIn('slow').removeClass('hidden');

                transferComplete = null;
            }
            // var placeHolder = $('div#import-status-placeholder');

            if (r.status == 'Complete') {
                transferComplete = true;
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing " + r.status + ".</a>");
                cancelButton.fadeIn('slow').removeClass('hidden');
                checkAll.fadeIn('slow').removeClass('hidden');
            } else if (r.status == 'Incomplete' || r.status == 'Processing') {
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing." + "</a>");
            }
        });
    },
    getRemainingInvalidData: function () {
        CsvImportStatusManager.callAsync('/import-activity/remaining-invalid-data', 'GET').success(function (response) {
            CsvImportStatusManager.invalidData(response);
        });
    },
    getRemainingValidData: function () {
        CsvImportStatusManager.callAsync('/import-activity/remaining-valid-data', 'GET').success(function (response) {
            CsvImportStatusManager.validData(response);
        });
    },
    getData: function () {
        CsvImportStatusManager.callAsync('get-data', 'GET').success(function (response) {
            if (response.validData) {
                CsvImportStatusManager.validData(response.validData);
            }

            if (response.invalidData) {
                CsvImportStatusManager.invalidData(response.invalidData);
            }
        }).error(function (error) {
            var validParentDiv = CsvImportStatusManager.getParentDiv('valid-data');
            var invalidParentDiv = CsvImportStatusManager.getParentDiv('invalid-data');

            validParentDiv.append('Looks like something went wrong.');
            invalidParentDiv.append('Looks like something went wrong.');
        });
    },
    invalidData: function (response) {
        var invalidParentDiv = CsvImportStatusManager.getParentDiv('invalid-data');
        var allDataParentDiv = CsvImportStatusManager.getParentDiv('all-data');

        if (invalidParentDiv.html() != 'No data available.') {
            invalidParentDiv.append(response.render);
        }
        if (response.render != '<p>No data available.</p>') {
            var inValidDiv = $("<div class='invalid-data-all' style='border-left: 6px solid #e15454'></div>");
            allDataParentDiv.append(inValidDiv);
            inValidDiv.append(response.render);
        }
    },
    validData: function (response) {
        var validParentDiv = CsvImportStatusManager.getParentDiv('valid-data');
        var allDataParentDiv = CsvImportStatusManager.getParentDiv('all-data');

        if (validParentDiv.html() != 'No data available.') {
            validParentDiv.append(response.render);
        }
        if (response.render != '<p>No data available.</p>') {
            var validDiv = $("<div class='valid-data-all' style='border-left: 6px solid #80CA9C'></div>");
            allDataParentDiv.append(validDiv);
            validDiv.append(response.render);
        }
    }
};

$(document).ready(function () {
    if (!alreadyProcessed) {
        accordionInit();
        clearInvalidButton.hide();
        var placeHolder = $('div#import-status-placeholder');
        placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing." + "</a>");

        var interval = setInterval(function () {
            CsvImportStatusManager.isTransferComplete();

            if (CsvImportStatusManager.ifParentIsEmpty('invalid-data') && CsvImportStatusManager.ifParentIsEmpty('valid-data')) {
                CsvImportStatusManager.getData();
            } else {
                CsvImportStatusManager.getRemainingValidData();
                CsvImportStatusManager.getRemainingInvalidData();
            }

            if (null == transferComplete) {
                window.location = '../import-activity/upload-csv-redirect';
            }

            if (transferComplete) {
                accordionInit();
                CsvImportStatusManager.enableImport();
                clearInterval(interval);
            }
        }, 3000);
    } else {
        accordionInit();

        placeHolder = $('div#import-status-placeholder');
        placeHolder.empty().append("<a href='/import-activity/import-status'>" + "CSV File Processing Complete."+"</a>");

        cancelButton.fadeIn('slow').removeClass('hidden');
        CsvImportStatusManager.enableImport();
    }
});
