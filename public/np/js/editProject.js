$('#project-country-edit').on('change', function () {
    locationCount = 0;
    currentLocationCount = 0;
    var that = $(this);

    Project.setForm(that.parent().find('#project-form')).improviseForm(that.val(), true);
});
