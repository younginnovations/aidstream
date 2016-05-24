var basicInfoForm = $('div#basic-info');
var otherInfoForm = $('div#other-info');
var yesDelete;
var deleteForm;

var Project = {
    nextStep: function () {
        basicInfoForm.hide();
        otherInfoForm.removeClass('hidden');
    },
    previousStep: function () {
        basicInfoForm.show();
        otherInfoForm.addClass('hidden');
    },
    confirmDelete: function () {
        var modal = $('#projectDeleteModal');
        var clone = modal.clone();

        clone.modal('show');

        yesDelete = clone.find('button#yes-delete');

        return this;
    },
    setDeleteForm: function (form) {
        deleteForm = form;

        return this;
    },
    destroy: function () {
        deleteForm.submit();
    }
};

$('#projectNextStep').on('click', function () {
    Project.nextStep();
});

$('#projectPreviousStep').on('click', function () {
    Project.previousStep();
});

$('a.delete-project').on('click', function () {
    Project.setDeleteForm($(this).parent().find('form#project-delete-form')[0]).confirmDelete();

    yesDelete.on('click', function () {
        Project.destroy();
    });
});
