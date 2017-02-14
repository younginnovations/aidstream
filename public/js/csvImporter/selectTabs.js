$('select.tab-select').on('change', function () {
    var self = $(this);

    var existentData = $('#valid').find('div .existent-data').clone();
    var newData = $('#valid').find('div .new-data').clone();

    var selectedOption = self.find('option:selected');
    var unselectedOptions = self.find('option:not(:selected)');

    unselectedOptions.each(function (key, option) {
        var className = $(option).attr('data-select');
        $('#' + className).removeClass('active');
    });

    var choice = selectedOption.attr('data-select');

    var parent = $('#' + choice);

    if (existentData != false && choice == 'existing') {
        var existentDataContainer = $('<div/>', {
            class: 'panel panel-default'
        });

        var existentDataForm = parent.children('form');

        if (existentDataForm.find('div').length <= 0) {
            existentDataContainer.append(existentData);
            existentDataForm.append(existentData);
        }
    }

    if (newData != false && choice == 'new') {
        var newDataContainer = $('<div/>', {
            class: 'panel panel-default'
        });
        var newDataForm = parent.children('form');

        if (newDataForm.find('div').length <= 0) {
            newDataContainer.append(newData);
            newDataForm.append(newData);
        }
    }
    parent.addClass('active');
});
