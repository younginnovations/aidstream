var transactionForm = $('#transaction-form-clone');
var dom = $('div.create-activity-form');
var count = 0;

var Transaction = {
    /*
     * Add more form fields for current TransactionType.
     */
    addMoreTransaction: function () {
        Transaction.increaseCount();
        Transaction.appendFields(Transaction.clone());
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
        var tempDiv = $('<div/>',{
            class: 'added-new-block'
        }).append(Transaction.index(transaction));

        form.before(tempDiv);
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

// $(document).ready(function () {
//     $('.datepicker').datepicker();
// });
