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
var existingDiv = $('.existing-data');
var newDiv = $('.new-data');
var csvImporter;
var clickCounter = 0;
var dontOverwrite = $('div#dontOverwrite');
var status = '';

var CsvImportStatusManager = {
    localisedData: '',
    localisedDataLoaded: false,
    getParentDiv: function (selector) {
        return $('div.' + selector);
    },
    enableImport: function () {
        submitButton.fadeIn("slow").removeClass('hidden');
        dontOverwrite.fadeIn("slow").removeClass('hidden');
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

        CsvImportStatusManager.callAsync('/activity/' + activityId + '/import-transaction/check-status', 'GET').success(function (response) {
            if (response.status == 'header_mismatch' || response.status == 'no_ongoing_process' || response.status == 'no_data_available') {
                status = response.status;
                cancelButton.fadeIn('slow').removeClass('hidden');
                transferComplete = null;
            }

            if (response.status == 'Completed') {
                transferComplete = true;
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing'] + ' ' + response.status + ".</a>");
                cancelButton.fadeIn('slow').removeClass('hidden');
                checkAll.fadeIn('slow').removeClass('hidden');
            } else if (response.status == 'Incomplete' || response.status == 'Processing') {
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + CsvImportStatusManager.localisedData['csv_file_processing'] + "</a>");
            }

        });
    },
    getData: function () {
        CsvImportStatusManager.callAsync('/activity/' + activityId + '/import-transaction/get-data', 'GET').success(function (response) {
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
        if (response.render != '<p>No data available</p>') {
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

        if (response.render != '<p>No data available</p>') {
            var validDiv = $("<div class='valid-data-all' style='border-left: 6px solid #80CA9C'></div>");
            allDataParentDiv.append(validDiv);
            validDiv.append(response.render);
            CsvImportStatusManager.filterValidData(response);
        }
    },
    filterValidData: function (response) {
        var divs = $(response.render).find('.badge');
        var existingData = [];
        var newData = [];
        $.each(divs, function (index, div) {
            if (($(div).html()) == 'Existing') {
                existingData.push($(this).closest('.panel-heading'));
            } else {
                newData.push($(this).closest('.panel-heading'));
            }
        });

        $.each(existingData, function (index, div) {
            existingDiv.append(div);
        });

        $.each(newData, function (index, div) {
            newDiv.append(div);
        })
    },
    loadLocalisedText: function () {
        this.callAsync('/import-activity/localisedText', 'get').success(function (data) {
            CsvImportStatusManager.localisedData = JSON.parse(data);
            CsvImportStatusManager.localisedDataLoaded = true;
            csvImporter();
            accordionInit();
        });
    },
    getRemainingValidData: function () {
        this.callAsync('/activity/' + activityId + '/import-transaction/get-remaining-valid-data', 'GET').success(function (response) {
            if (!$.isEmptyObject(response.validData)) {
                CsvImportStatusManager.validData(response.validData);
            }
        });
    },
    getRemainingInvalidData: function () {
        this.callAsync('/activity/' + activityId + '/import-transaction/get-remaining-invalid-data', 'GET').success(function (response) {
            if (!$.isEmptyObject(response.invalidData)) {
                CsvImportStatusManager.invalidData(response.invalidData);
            }
        });
    },
    preventOverwrite: function () {
        dontOverwrite.on('click', function (e) {
            e.preventDefault();
            var allValid = $('.valid-data-all').find('.badge');
            var valid = $('.valid-data').find('.badge');
            var existing = $('.existing-data').find('.badge');

            CsvImportStatusManager.prevent(allValid);
            CsvImportStatusManager.prevent(valid);
            CsvImportStatusManager.prevent(existing);

            clickCounter++;
        });
    },
    prevent: function (selector) {
        $.each(selector, function (index, div) {
            if ($(div).html() == 'Existing') {
                if (clickCounter % 2 == 0) {
                    $(div).parent().parent().find('input').attr('disabled', 'disabled');
                    dontOverwrite.find('input').prop('checked', true);
                } else {
                    $(div).parent().parent().find('input').removeAttr('disabled');
                    dontOverwrite.find('input').prop('checked', false);
                }
            }
        });
    }
};

$(document).ready(function () {
    CsvImportStatusManager.loadLocalisedText();
    csvImporter = function () {
        clearInvalidButton.hide();

        var interval = setInterval(function () {
            CsvImportStatusManager.isTransferComplete();
            if (transferComplete) {
                clearInterval(interval);
                CsvImportStatusManager.enableImport();
                accordionInit();
            } else {
                if (CsvImportStatusManager.ifParentIsEmpty('invalid-data') && CsvImportStatusManager.ifParentIsEmpty('valid-data')) {
                    CsvImportStatusManager.getData();
                } else {
                    CsvImportStatusManager.getRemainingValidData();
                    CsvImportStatusManager.getRemainingInvalidData();
                }
            }

            if (transferComplete == null) {
                window.location = window.location.origin + '/activity/' + activityId + '/import-transaction/upload-csv-redirect/';
            }
        }, 5000);
    };
    CsvImportStatusManager.preventOverwrite();
});

