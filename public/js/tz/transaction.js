var transactionForm = $('#transaction-form-clone');
var dom = $('div.create-activity-form');
var count = currentTransactionCount;

var Transaction = {
    /*
     * Add more form fields for current TransactionType.
     */
    addMoreTransaction: function () {
        var clone = Transaction.clone();

        Transaction.increaseCount();
        Transaction.appendFields(clone);
    },
    /*
     * Show the confirm delete modal window.
     */
    confirmDelete: function () {
        var modal = $('#transactionDeleteModal');
        var clone = modal.clone();

        clone.modal('show');

        yesDelete = clone.find('button#yes-delete');

        return this;
    },
    /*
     * Clone the form fields from the DOCUMENT.
     */
    clone: function () {
        return transactionForm.clone();
    },
    /*
     * Set the form to be submitted.
     */
    setForm: function (formParam) {
        form = formParam;

        return this;
    },
    /*
     * Trigger form submission.
     */
    submitForm: function () {
        form.submit();
    },
    /*
     * Re-index the added form fields according to their counts.
     */
    index: function (transaction) {
        return transaction.html().replace(/index/g, count)
    },
    /*
     * Append the fields into the DOCUMENT.
     */
    appendFields: function (transaction) {
        var form = dom.find('form').find('#submit-transaction');

        var clone = Transaction.index(transaction);

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(clone);

        form.before(tempDiv);

        $(tempDiv).find('.added-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            formatDate: 'Y-m-d',
            scrollMonth: false
        });

        $('form select').select2();
    },
    /*
     * Increase the index count.
     */
    increaseCount: function () {
        count++;
        currentTransactionCount++;
    }
};

$('#add-more-transaction').on('click', function () {
    count = currentTransactionCount;

    Transaction.addMoreTransaction();
});

var removeBlock = function (element) {
    count = currentTransactionCount;
    count--;

    $(element).parent().remove();
};

$('#add-more-transaction-edit').on('click', function () {
    count = currentTransactionCount;

    Transaction.addMoreTransaction();
});

