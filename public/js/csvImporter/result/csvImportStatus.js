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
        CsvImportStatusManager.callAsync('/activity/' + activity + '/import-result/check-status', 'GET').success(function (response) {
            var r = JSON.parse(response);

            if (r.status == 'Error') {
                cancelButton.fadeIn('slow').removeClass('hidden');

                transferComplete = null;
            }

            if (r.status == 'Complete') {
                transferComplete = true;
                cancelButton.fadeIn('slow').removeClass('hidden');
                checkAll.fadeIn('slow').removeClass('hidden');
            }
        }).error(function (error) {
            // TODO: handle error
        });
    },
    getRemainingInvalidData: function () {
        CsvImportStatusManager.callAsync('/activity/' + activity + '/import-result/remaining-invalid-data', 'GET').success(function (response) {
            CsvImportStatusManager.invalidData(response);
        });
    },
    getRemainingValidData: function () {
        CsvImportStatusManager.callAsync('/activity/' + activity + '/import-result/remaining-valid-data', 'GET').success(function (response) {
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
    accordionInit();
    clearInvalidButton.hide();

    var interval = setInterval(function () {
        CsvImportStatusManager.isTransferComplete();

        if (CsvImportStatusManager.ifParentIsEmpty('invalid-data') && CsvImportStatusManager.ifParentIsEmpty('valid-data')) {
            CsvImportStatusManager.getData();
        } else {
            CsvImportStatusManager.getRemainingValidData();
            CsvImportStatusManager.getRemainingInvalidData();
        }

        if (null == transferComplete) {
            window.location = window.location.origin + '/activity/' + activity + '/import-result/upload-csv-redirect';
        }

        if (transferComplete) {
            accordionInit();
            clearInterval(interval);
            CsvImportStatusManager.enableImport();
        }
    }, 5000);
});
