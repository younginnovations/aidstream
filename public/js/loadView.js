var View = {
    csrfToken: '',
    id: '',
    callAsync: function (url, data) {
        return $.ajax({
            headers: View.csrfToken,
            method: 'POST',
            url: url,
            data: data
        });
    },
    init: function (csrfToken, id) {
        View.csrfToken = csrfToken;
        View.id = id;
        View.loadTransaction();
        View.loadResult();
    },
    loadTransaction: function () {
        this.callAsync('/activity/getTransactionView', {'id': View.id})
            .success(function (data) {
                $('.activity-element-wrapper:last').parent().append(data);
                // View.loadResult()
            });
    },
    loadResult: function () {
        this.callAsync('/activity/getResultView', {'id': View.id})
            .success(function (data) {
                $('.activity-element-wrapper:last').parent().append(data);
            });
    }
};
