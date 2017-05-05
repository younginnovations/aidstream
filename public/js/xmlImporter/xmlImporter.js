var xmlImportCompleted = false;

var XmlImporter = {
    localisedData: '',
    statusCode: {
        incomplete: 101,
        file_not_found: 102,
        complete: 103,
        schema_error: 104,
        version_error: 105,
        processing_error: 106
    },
    callAsync: function (url, methodType) {
        return $.ajax({
            url: url,
            type: methodType
        });
    },
    status: function () {
        this.callAsync('/xml-import/import-status', 'get').success(function (data) {
            if (data == XmlImporter.statusCode['schema_error']) {
                window.location.href = 'xml-import/schemaErrors';
            }

            if (data.currentActivityCount && data.totalActivities) {
                $('#xml-import-status-placeholder').html(
                    "<div class='alert alert-success'>"
                    + data.currentActivityCount + "/" + data.totalActivities + " activities processed. "
                    + XmlImporter.localisedData['failed'] + ": " + data.failed + " " + XmlImporter.localisedData['success'] + " : " + data.success
                    + "</div>");
            }
        });
    },
    checkCompletion: function () {
        if (!xmlImportCompleted) {
            this.callAsync('/xml-import/isCompleted', 'get').success(function (data) {
                if (data.status == XmlImporter.statusCode['version_error']) {
                    $('#xml-import-status-placeholder').html(
                        "<div class='alert alert-danger'>"
                        + XmlImporter.localisedData['invalid_xml_version']
                        + "</div>"
                    );
                    xmlImportCompleted = true;
                }

                if (data.status == XmlImporter.statusCode['processing_error']) {
                    $('#xml-import-status-placeholder').html(
                        "<div class='alert alert-danger'>"
                        + XmlImporter.localisedData['processing_error']
                        + "</div>"
                    );
                    xmlImportCompleted = true;
                }

                if (data.status == XmlImporter.statusCode['complete']) {
                    xmlImportCompleted = true;
                }

                if (data.status == XmlImporter.statusCode['file_not_found']) {
                    $('#xml-import-status-placeholder').html(
                        "<div class='alert alert-danger'>"
                        + XmlImporter.localisedData['xml_file_incorrect']
                        + "</div>"
                    );
                    xmlImportCompleted = true;
                }
            });
        }
    },
    complete: function () {
        return this.callAsync('/xml-import/complete', 'get');
    },
    reloadPage: function () {
        location.reload();
    },
    getLocalisedText: function () {
        this.callAsync('/xml-import/localisedText', 'get').success(function (data) {
            XmlImporter.localisedData = JSON.parse(data);
        });
    }
};

$('document').ready(function () {
    XmlImporter.getLocalisedText();
    var interval = setInterval(function () {
        if (xmlImportCompleted) {
            XmlImporter.complete();
            clearInterval(interval);
            XmlImporter.reloadPage();
        } else {
            XmlImporter.status();
            XmlImporter.checkCompletion();
        }
    }, 4000);
});
