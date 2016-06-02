var transactionForm = $('#transaction-form-clone');
var dom = $('div.create-activity-form');
var count = 0;

var Transaction = {
    /*
     * Add more form fields for current TransactionType.
     */
    addMoreTransaction: function (existingTransactionCount) {
        var clone = Transaction.clone();

        if (existingTransactionCount) {
            count = existingTransactionCount;
        }

        Transaction.increaseCount();
        Transaction.appendFields(clone);
    },
    /*
     * Clone the form fields from the DOCUMENT.
     */
    clone: function () {
        return transactionForm.clone();
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
    },
    /*
     * Increase the index count.
     */
    increaseCount: function () {
        count++;
    }
};

$('#add-more-transaction').on('click', function () {
    Transaction.addMoreTransaction();
});

var removeBlock = function (element) {
    count--;
    $(element).parent().remove();
};

$('#add-more-transaction-edit').on('click', function () {
    Transaction.addMoreTransaction(currentTransactionCount);
});


// $(document).ready(function () {
//     $('.datepicker').datepicker();
// });
