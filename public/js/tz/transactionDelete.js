$('a.delete-transaction').on('click', function () {
    Transaction.setForm($(this).parent().find('form#transaction-delete-form')[0]).confirmDelete();

    yesDelete.on('click', function () {
        Transaction.submitForm();
    });
});
