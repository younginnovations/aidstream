var budgetCount = 0;

var addMoreBudget = function (context) {
    budgetCount++;
    currentBudgetCount++;
    var clone = Project.clone($('#budget-clone'), budgetCount);

    var temp = $('<div/>', {
        class: 'added-new-block'
    }).append(clone);

    $(context).before(temp);

    $(temp).find('.datepicker').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        scrollMonth: false
    });

    $('form select').select2();
};

$('#add-more-budget').on('click', function () {
    budgetCount = currentBudgetCount;

    addMoreBudget(this);
});

$('#add-more-budget-edit').on('click', function () {
    budgetCount = currentBudgetCount;

    addMoreBudget(this);
});

var removeBudget = function (element) {
    $(element).parent().remove();
};
