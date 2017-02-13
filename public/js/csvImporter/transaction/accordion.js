var accordionInit = function () {
    $(".invalid-data .panel-default .panel-heading").on('click', 'label', function () {
        $(this).children('.data-listing').slideToggle();
    });

    $(".invalid-data-all .panel-default .panel-heading").on('click', 'label', function () {
        $(this).children('.data-listing').slideToggle();
    });
};

$('.check-btn').on('change', function () {
    $("input[type=checkbox]:not(:disabled)").not('input.overwrite-btn').prop('checked', $(this).prop('checked'));
});

