var UserOnBoarding = {
    addHintLabel: function () {
        $("[data-step='2']").attr({
            'data-hint': "<p>Click here to view the list of Activities you have added</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(2)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(3)'>Next</button>",
            'data-position': 'right'
        });

        $("[data-step='0']").attr({
            'data-hint': "<p>Hover over here to get options to add an activity</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(0)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(2)'>Next</button>",
            'data-position': 'top-left'
        });

        $("[data-step='3']").attr({
            'data-hint': "<p>Click here to view your organisation's data which you can publish/update to the IATI Registry</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(3)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(4)'>Next</button>",
            'data-position': 'right'
        });

        $("[data-step='4']").attr({
            'data-hint': "<p>Click here to view your published activity and organisation data files.</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(4)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(5)'>Next</button>",
            'data-position': 'right'
        });

        $("[data-step='5']").attr({
            'data-hint': "<p>Click here to view all the documents you have uploaded from document link in the activities</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(5)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(6)'>Next</button>",
            'data-position': 'right'
        });

        $("[data-step='6']").attr({
            'data-hint': "<p>Click here to get download options for your data on AidStream</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(6)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(7)'>Next</button>",
            'data-position': 'right'
        });

        var nextStep = (roleId == 1) ? 8 : 1;
        $("[data-step='7']").attr({
            'data-hint': "<p>Click here to view the settings of your organisation. Only the users with Administrator level permission can edit settings</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(7)'>Skip Dashboard Tour</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(" + nextStep + ")'>Next</button>",
            'data-position': 'right'
        });

        if (roleId == 1) {
            $("[data-step='8']").attr({
                'data-hint': "<p>Click here to view the changes made by users</p>" +
                "<a href='#' class='pull-left skip-tour' onclick='skip(8)'>Skip Dashboard Tour</a>" +
                "<button class='pull-right nextBtn' onclick='goNext(1)'>Next</button>",
                'data-position': 'right'
            });
        }

        $("[data-step='1']").attr({
            'data-hint': "<p>Hover over here and click on “My Profile” to go your profile page</p>" +
            "<button class='pull-left nextBtn closeBtn' onclick='skip()'>Complete tour</button>",
            'data-position': 'bottom-right'
        });
    },
    storeHintStatus: function (status) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
            url: '/hintStatus',
            data: {status: status},
            type: 'POST',
            success: function (data) {
            }
        });
    },
    dashboardTour: function () {
        var intro = introJs();

        intro.setOptions({
            hintButtonLabel: '',
            hintPosition: 'top-left'
        });
        intro.addHints();
    },
    finalHints: function () {
        var intro = introJs();
        intro.setOptions({
            steps: [
                {
                    element: '#admin-dropdown',
                    intro: "<div>" +
                    "<p>You can always start the Dashboard tour again by turning this toggle on</p>" +
                    "<button class='pull-left nextBtn closeBtn' onclick='endTour()'>Close</button> </div>",
                    position: 'left'
                }
            ],
            exitOnOverlayClick: false,
            showStepNumbers: false,
            showButtons: false,
            showBullets: false,
            exitOnEsc: false,
            keyboardNavigation: false
        });

        intro.start();
    },
    settingsTour: function (completedSteps) {
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

        // For I'll set this up later
        $('body').delegate('#info1', 'click', function () {
            calculateNextStep(2);
        }).delegate('#info2', 'click', function () {
            calculateNextStep(3);
        }).delegate('#info3', 'click', function () {
            calculateNextStep(4);
            // window.location.href = '/activity-elements-checklist#4';
        }).delegate('#info4', 'click', function () {
            calculateNextStep(5);
            // window.location.href = '/default-values#5';
        }).delegate('#info5', 'click', function () {
            calculateNextStep(6);
            // window.location.href = '/activity';
        });
        //

        // Update and go to next step
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
                        calculateNextStep(2);
                        // intro.goToStep(2);
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
                    calculateNextStep(3);
                    // intro.goToStep(3);
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
                    calculateNextStep(4);
                    // window.location.href = '/activity-elements-checklist#4';
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

        function calculateNextStep(step) {
            console.log(step, completedSteps);

            var completedStep = completedSteps[0].sort();
            console.log(completedStep);
            for (var i = 0; i < completedStep.length; i++) {
                if (step == completedStep[i]) {
                    step = step + 1;
                }
            }
            nextStep(step);
        }

        function nextStep(step) {
            if (step == 2) {
                intro.goToStep(2);
            } else if (step == 3) {
                intro.goToStep(3);
            } else if (step == 4) {
                window.location.href = '/activity-elements-checklist#4';
            } else if (step == 5) {
                window.location.href = '/default-values#5';
            } else {
                window.location.href = '/check-onboarding-step';
            }
        }
    },
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
    },
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