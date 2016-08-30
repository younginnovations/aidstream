var accordionInit = function () {
    $(".invalid-data .panel-default .panel-heading").on('click', 'label', function () {
        $(this).children('.data-listing').slideToggle();
    });
};

$('#check-all').on('change', function () {
    $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
});
