var xmlImportCompleted = false;

var XmlImporter = {
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
                    + data.currentActivityCount + " out of " + data.totalActivities + " activities processed. "
                    + "Failed: " + data.failed + " Success: " + data.success
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
                    + "Sorry the xml file you uploaded is incorrect"
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
    }
};

$('document').ready(function () {
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
