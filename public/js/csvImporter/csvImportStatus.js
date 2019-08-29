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
var csvImporter;

var CsvImportStatusManager = {
    localisedData: '',
    localisedDataLoaded: false,
    getParentDiv: function (selector) {
        return $('div.' + selector);
    },
    enableImport: function () {
        submitButton.fadeIn("slow").removeClass('hidden');
        $('#dontOverwrite').fadeIn("slow").removeClass('hidden');
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
        placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing'] + "</a>");

        CsvImportStatusManager.callAsync('/import-activity/check-status', 'GET').success(function (response) {
            var r = JSON.parse(response);

            if (r.status == 'Error') {
                transferComplete = 'error';
                cancelButton.fadeIn('slow').removeClass('hidden');
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['error_processing_csv'] + "</a>");
            }

            if (r.status == 'Complete') {
                transferComplete = true;
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing'] + ' ' + r.status + ".</a>");
                cancelButton.fadeIn('slow').removeClass('hidden');
                checkAll.fadeIn('slow').removeClass('hidden');
            } else if (r.status == 'Incomplete' || r.status == 'Processing') {
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing'] + "</a>");
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

            validParentDiv.append(CsvImportStatusManager.localisedData['something_went_wrong']);
            invalidParentDiv.append(CsvImportStatusManager.localisedData['something_went_wrong']);
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
    },
    loadLocalisedText: function () {
        this.callAsync('/import-activity/localisedText', 'get').success(function (data) {
            CsvImportStatusManager.localisedData = JSON.parse(data);
            CsvImportStatusManager.localisedDataLoaded = true;
            csvImporter();
        });
    }
};

$(document).ready(function () {
    CsvImportStatusManager.loadLocalisedText();

    csvImporter = function () {
        if (!alreadyProcessed) {
            accordionInit();
            clearInvalidButton.hide();
            var placeHolder = $('div#import-status-placeholder');
            placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing'] + "</a>");

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
                if (transferComplete == 'error') {
                    submitButton.addClass('hidden');
                    $('#dontOverwrite').addClass('hidden');
                    cancelButton.addClass('hidden');
                    $('#go_back').removeClass('hidden').css({ "position": "relative", "color": "white", "left": "790px", "top": "-27px", "text-decoration": "underline"});
                }
            }, 3000);
        } else {
            accordionInit();
            placeHolder = $('div#import-status-placeholder');
            placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing_completed'] + "</a>");

            cancelButton.fadeIn('slow').removeClass('hidden');
            CsvImportStatusManager.enableImport();
            checkAll.fadeIn('slow').removeClass('hidden');
        }
    }
});

Importer = {
    status: function () {
        $('.override').on('change', function (e) {
            if (e.target.checked) {
                $('input[type="checkbox"].existence').attr('disabled', true);
            }
            if (!e.target.checked) {
                $('input[type="checkbox"].existence').attr('disabled', false);
            }
        });

        $('.override').trigger('change');
    }
};

Importer.status();
