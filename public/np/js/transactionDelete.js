$('a.delete-transaction').on('click', function () {
    var form = $(this).parent().find('form');

    Transaction.setForm(form).confirmDelete();

    yesDelete.on('click', function () {
        Transaction.submitForm();
    });
});

$('input.delete-transaction').on('click', function (e) {
    e.preventDefault();
    Transaction.setForm($(this).parent()[0]).confirmDelete();

    yesDelete.on('click', function (e) {
        Transaction.submitForm();
    });
});
