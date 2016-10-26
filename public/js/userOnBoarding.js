var UserOnBoarding = {
    addHintLabel: function () {
        if (dashboardSteps.indexOf(2) == -1) {
            $("[data-step='1']").attr({
                'data-hint': "<a href='#' id='closeHint' onclick='hideHints(2)' class='close-hints'>x</a>" +
                "<p>Click here to view the list of Activities you have added</p>", 'data-position': 'right'
            });
            // + "<button class='nextBtn' onclick='goNext(0)'>Go to Next</button>",'data-position': 'right'});
        }

        if (dashboardSteps.indexOf(0) == -1) {
            $("[data-step='2']").attr('data-hint', "<a href='#' onclick='hideHints(0)' class='close-hints' class='closeHint'>x</a>" +
                "<p>Hover over here to get options to add an activity</p>");
            // "<button class='nextBtn' onclick='goNext(3)'>Go to Next</button>");
        }

        if (dashboardSteps.indexOf(3) == -1) {
            $("[data-step='3']").attr({
                'data-hint': "<a href='#' onclick='hideHints(3)' class='close-hints'>x</a>" +
                "<p>Click here to view your organisation's which you can publish/update to the IATI Registry</p>", 'data-position': 'right'
            });
            // "<button class='nextBtn' onclick='goNext(4)'>Go to Next</button>", 'data-position': 'right'
            // });
        }

        if (dashboardSteps.indexOf(4) == -1) {
            $("[data-step='4']").attr({
                'data-hint': "<a href='#' onclick='hideHints(4)' class='close-hints'>x</a>" +
                "<p>Click here to view your published activity or organisation files.</p>", 'data-position': 'right'
            });
            // "<button class='nextBtn' onclick='goNext(5)'>Go to Next</button>", 'data-position': 'right'
            // });
        }

        if (dashboardSteps.indexOf(5) == -1) {
            $("[data-step='5']").attr({
                'data-hint': "<a href='#' onclick='hideHints(5)' class='close-hints'>x</a>" +
                "<p>Click here to view the documents you have added under document link</p>", 'data-position': 'right'
            });
            // "<button class='nextBtn' onclick='goNext(6)'>Go to Next</button>",
        }

        if (dashboardSteps.indexOf(6) == -1) {
            $("[data-step='6']").attr({
                'data-hint': "<a href='#' onclick='hideHints(6)' class='close-hints'>x</a>" +
                "<p>Click here to get options to download your data which is on AidStream</p>", 'data-position': 'right'
            });
            // "<button class='nextBtn' onclick='goNext(7)'>Go to Next</button>", 'data-position': 'right'
            // });
        }

        if (dashboardSteps.indexOf(7) == -1) {
            $("[data-step='7']").attr({
                'data-hint': "<a href='#' onclick='hideHints(7)' class='close-hints'>x</a>" +
                "<p>Click here to view your organisation's account settings. Users with Administrator level permission can edit settings from here</p>", 'data-position': 'right'
            });
            // "<button class='nextBtn' onclick='goNext(8)'>Go to Next</button>", 'data-position': 'right'
            // });
        }

        if (dashboardSteps.indexOf(8) == -1 && roleId == 1) {
            $("[data-step='8']").attr({
                'data-hint': "<a href='#' onclick='hideHints(8)' class='close-hints'>x</a>" +
                "<p>Click here to view the changes made by users</p>", 'data-position': 'right'
            });
            // "<button class='nextBtn' onclick='goNext(1)'>Go to Next</button>", 'data-position': 'right'
            // });
        }

        if (dashboardSteps.indexOf(1) == -1) {
            $("[data-step='9']").attr('data-hint', "<p><a href='#' onclick='hideHints(1)' class='close-hints'>x</a>" +
                "<p>Hover over here and click on “My Profile” to go your profile page</p>");
        }
    },
    dashboardTour: function () {
        var intro = introJs();

        intro.setOptions({
            hintButtonLabel: '',
            hintPosition: 'top-left'
        });
        intro.addHints();
    },
    settingsTour: function () {
        var intro = introJs();
        intro.setOptions({
            steps: [
                {
                    element: '#publishing_info1',
                    intro: '' +
                    '<div class="intro-title text-center">AidStream required your organisation&rsquo;s Publisher ID and API key to be able to publish your data to the IATI registry.</div>' +
                    '<a id="btnInfo1" class="btn update-next">Update and go to next step</a>' +
                    '<a id="info1" class="setup-later">I&rsquo;ll set this up later</a>',
                    position: 'bottom-middle-aligned'
                },
                {
                    element: '#publishing_info2',
                    intro: '' +
                    '<div class="intro-title text-center">The publishing type is “Unsegmented” by default.All the activities will be published in a single file to the IATI registry.</div>' +
                    '<a id="btnInfo2" class="btn update-next">Update and go to next step</a>' +
                    '<a id="info2" class="setup-later">I&rsquo;ll set this up later</a>',
                    position: 'bottom-middle-aligned'
                },
                {
                    element: '#publishing_info3',
                    intro: '' +
                    '<div class="intro-title text-center">By default, when you update your data, the changes are not automatically reflected on the IATI registry. Select “Yes” to be let AidStream automatically update your data on the registry.</div>' +
                    '<a id="btnInfo3" class="btn update-next">Update and go to next step</a>' +
                    '<a id="info3" class="setup-later">I&rsquo;ll set this up later</a>',
                    position: 'top'
                },
                {
                    element: '#activity-elements-checklist-wrapper',
                    intro: '' +
                    '<div class="intro-title">On AidStream some of the elements are required for an activity. These elements are <span class="disabled-check-img"></span> checked in the list below and disabled. You can always <span class="add-check-img"></span> check other elements to add their information in your activities.</div>' +
                    '<a id="btnInfo4" class="btn update-next">Update and go to next step</a>' +
                    '<a id="info4" class="setup-later">I&rsquo;ll set this up later</a>',
                    position: 'bottom-middle-aligned'
                },
                {
                    element: '#default_values',
                    intro: '' +
                    '<div class="intro-title">These default values are used throughout  the AidStream for your organisation and activities information.</div>' +
                    '<a id="btnInfo5" class="btn update-next">Finish set up process</a>' +
                    '<a id="info5" class="setup-later">I&rsquo;ll set this up later</a>',
                    position: 'bottom-middle-aligned'
                }
            ],
            exitOnOverlayClick: false,
            showStepNumbers: false,
            showButtons: false,
            exitOnEsc: false,
            keyboardNavigation: false
        });

        intro.start();

        var links = {
            '1': '/publishing-settings/#1',
            '2': '/publishing-settings/#2',
            '3': '/publishing-settings/#3',
            '4': '/activity-elements-checklist/#4',
            '5': '/default-values/#5'
        };

        var steps = $('.introjs-bullets a').each(function (index) {
            var stepNumber = $(this).attr('data-stepnumber');
            $(this).attr('href', links[stepNumber]);
        });

        var stepNumber = location.hash.replace('#', '');
        if (stepNumber != '') {
            intro.goToStep(stepNumber);
        }

        $('body').delegate('#info1', 'click', function () {
            intro.goToStep(2);
        }).delegate('#info2', 'click', function () {
            intro.goToStep(3);
        }).delegate('#info3', 'click', function () {
            window.location.href = '/activity-elements-checklist#4';
        }).delegate('#info4', 'click', function () {
            window.location.href = '/default-values#5';
        }).delegate('#info5', 'click', function () {
            window.location.href = '/continueExploring';
        });

        $('body').delegate('#btnInfo1', 'click', function () {
            if ($('form').valid()) {
                var publisherId = $('#publisher_id').val();
                var apiId = $('#api_id').val();
                var publisherIdStatus = $('#publisher_id_status').val();
                var apiIdStatus = $('#api_id_status').val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                    url: '/savePublisherAndApiId',
                    data: {publisherId: publisherId, apiId: apiId, publisherIdStatus: publisherIdStatus, apiIdStatus: apiIdStatus},
                    type: 'POST',
                    success: function (data) {
                        intro.goToStep(2);
                    }
                });
            }
        });

        $('body').delegate('#btnInfo2', 'click', function () {
            var publishing = $('[name="publishing"]:checked').val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                url: '/savePublishingType',
                data: {publishing: publishing},
                type: 'POST',
                success: function (data) {
                    intro.goToStep(3);
                }
            });
        });

        $('body').delegate('#btnInfo3', 'click', function () {
            var publish_files = $('[name="publish_files"]:checked').val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                url: '/savePublishFiles',
                data: {publish_files: publish_files},
                type: 'POST',
                success: function (data) {
                    window.location.href = '/activity-elements-checklist#4';
                }
            });
        });

        $('body').delegate('#btnInfo4', 'click', function () {
            $('form').attr('action', '/saveActivityElementsChecklist');
            $('form button[type="submit"]').trigger('click');
        });

        $('form button[type="submit"]').hide();
        $('body').delegate('#btnInfo5', 'click', function () {
            $('#default_values form').valid();
            $('form').attr('action', '/saveDefaultValues');
            $('form button[type="submit"]').trigger('click');
        });
    }
    ,
    completedTour: function () {
        $('[name="check"]').change(function () {
            if (this.checked) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                    url: '/completeOnBoarding',
                    type: 'POST',
                    success: function () {
                        window.location.href = '/activity';
                    }
                });
            }
        });
    }
    ,
    validateDefaultValues: function () {
        var form = $('#default_values form');
        var validation = form.validate({
            errorClass: 'text-danger',
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.form-group').eq(0).addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.form-group').eq(0).removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                element.siblings('.text-danger').remove();
                element.siblings('.select2.select2-container').after(error);
            },
            invalidHandler: function () {
                $('button[type="submit"]', form).removeAttr('disabled');
            }
        });

        $('#default_currency', form).rules('add', {required: true, messages: {required: 'Default Currency is required.'}});
        $('#default_language', form).rules('add', {required: true, messages: {required: 'Default Language is required.'}});
    }
    ,
    validatePublishingInfo: function () {
        var form = $('form');
        var validation = form.validate({
            errorClass: 'text-danger',
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.form-group').eq(0).addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.form-group').eq(0).removeClass('has-error');
            },
            invalidHandler: function () {
                $('#btnInfo1').removeAttr('disabled');
            }
        });

        $('#publisher_id', form).rules('add', {required: true, messages: {required: 'Publisher id is required.'}});
        $('#api_id', form).rules('add', {required: true, messages: {required: 'Api Id is required.'}});
    }
};