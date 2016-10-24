$('select.tab-select').on('change', function () {
    var self = $(this);

    var selectedOption = self.find('option:selected');
    var unselectedOptions = self.find('option:not(:selected)');

    unselectedOptions.each(function (key, option) {
        var className = $(option).attr('data-select');
        $('#' + className).removeClass('active');
    });

    $('#' + selectedOption.attr('data-select')).addClass('active');
});
