var yesDelete;
var form;
var fundingOrganizationCount = 0;
var implementingOrganizationCount = 0;
var projectForm = $('#project-form');

var Project = {
    /*
     * Show the confirm delete modal window.
     */
    confirmDelete: function () {
        var modal = $('#projectDeleteModal');
        var clone = modal.clone();

        clone.modal('show');

        yesDelete = clone.find('button#yes-delete');

        return this;
    },
    /*
     * Set the form to be submitted.
     */
    setForm: function (formParam) {
        form = formParam;

        return this;
    },
    /*
     * Trigger form submission.
     */
    submitForm: function () {
        form.submit();
    },
    /*
     * Add more funding Organization.
     */
    addFundingOrganization: function () {
        fundingOrganizationCount++;
        var newFundingOrganization = Project.clone($('#funding-org'), fundingOrganizationCount);

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(newFundingOrganization);

        projectForm.find('#funding-wrap').find('#add-more-funding-organization').before(tempDiv);
    },
    /*
     * Add more Implementing Organization.
     */
    addImplementingOrganization: function () {
        implementingOrganizationCount++;
        var newImplementingOrganization = Project.clone($('#implementing-org'), implementingOrganizationCount)

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(newImplementingOrganization);

        projectForm.find('#implementing-wrap').find('#add-more-implementing-organization').before(tempDiv);
    },
    /*
     * Clone the form fields from the DOCUMENT.
     */
    clone: function (element, countByType) {
        var clone = element.clone();

        return clone.html().replace(/index/g, countByType)
    },
    /*
     * Remove the added block.
     */
    removeBlock: function (element, type) {
        if (type == 'implementing') {
            implementingOrganizationCount--;
        } else {
            fundingOrganizationCount--;
        }

        $(element).parent().remove();
    }
};

$('a.delete-project').on('click', function () {
    Project.setForm($(this).parent().find('form#project-delete-form')[0]).confirmDelete();

    yesDelete.on('click', function () {
        Project.submitForm();
    });
});

$('a#duplicate-project').on('click', function () {
    Project.setForm($(this).parent().find('form#project-duplicate-form')[0]).submitForm();
});

$('#add-more-funding-organization').on('click', function () {
    Project.addFundingOrganization();
});

$('#add-more-implementing-organization').on('click', function () {
    Project.addImplementingOrganization();
});

var removeFunding = function (element) {
    Project.removeBlock(element, 'funding');
};

var removeImplementing = function (element) {
    Project.removeBlock(element, 'implementing');
};

$(document).ready(function () {
    $('.datepicker').datepicker();
});
