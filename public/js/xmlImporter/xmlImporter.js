var xmlImportCompleted = false;

var XmlImporter = {
    localisedData: '',
    callAsync: function (url, methodType) {
        return $.ajax({
            url: url,
            type: methodType
        });
    },
    status: function () {
        this.callAsync('/xml-import/import-status', 'get').success(function (data) {

            if (data.error) {
                window.location.href = 'xml-import/schemaErrors';
            } else {
                $('#xml-import-status-placeholder').html(
                    "<div class='alert alert-success'>"
                    + data.currentActivityCount + "/" + data.totalActivities + " activities processed. "
                    + XmlImporter.localisedData['failed'] + ": " + data.failed + " " + XmlImporter.localisedData['success'] + " : " + data.success
                    + "</div>");
            }
        });
    },
    checkCompletion: function () {
        this.callAsync('/xml-import/isCompleted', 'get').success(function (data) {
            if (data.status == 'completed') {
                xmlImportCompleted = true;
            }

            if (data.status == 'file not found') {
                $('#xml-import-status-placeholder').html(
                    "<div class='alert alert-danger'>"
                    + XmlImporter.localisedData['xml_file_incorrect']
                    + "</div>"
                );
                xmlImportCompleted = true;
            }
        })
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
