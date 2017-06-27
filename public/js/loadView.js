var View = {
    csrfToken: '',
    id: '',
    elementName: '',
    callAsync: function (url, data, beforeSend, complete) {
        return $.ajax({
            headers: View.csrfToken,
            method: 'POST',
            url: url,
            data: data,
            beforeSend: function () {
                (beforeSend != null) ? beforeSend() : '';
            },
            complete: function () {
                (complete != null) ? complete() : '';
            }
        });
    },
    init: function (csrfToken, id) {
        View.csrfToken = csrfToken;
        View.id = id;
        View.loadTransaction();
        // View.loadResult();
    },
    loadTransaction: function () {
        this.elementName = 'Transaction';
        this.callAsync('/activity/getTransactionView', {'id': View.id}, View.beforeSend, View.complete)
            .success(function (data) {
                $('.activity-element-wrapper:last').parent().append(data);
                View.loadResult()
            });
    },
    loadResult: function () {
        this.elementName = 'Result';
        this.callAsync('/activity/getResultView', {'id': View.id}, View.beforeSend, View.complete)
            .success(function (data) {
                $('.activity-element-wrapper:last').parent().append(data);
            });
    },
    beforeSend: function () {
        $('.activity-element-wrapper:last').parent().append("" +
            "<div class='loading' style='color:rgba(72,72,72,.7);'>" +
            "Loading " + View.elementName + " Data..." +
            "</div>" +
            "")
    },
    complete: function () {
        $('.loading').remove();
    }
};
