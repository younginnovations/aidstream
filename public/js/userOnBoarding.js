var UserOnBoarding = {
    callAsync: function (url, methodType) {
        return $.ajax({
            url: url,
            type: methodType
        });
    },
    localisedFile: '',
    loadedLocalisedFile: false,
    getLocalisedText: function () {
        this.callAsync('/onBoarding/localisedText', 'get').success(function (data) {
            UserOnBoarding.localisedFile = JSON.parse(data);
            UserOnBoarding.loadedLocalisedFile = true;
            UserOnBoarding.addHintLabel();
            UserOnBoarding.dashboardTour();
        });
    },
    addHintLabel: function () {
        $("[data-step='2']").attr({
            'data-hint': "<p>" + this.localisedFile["step2"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(2)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(3)'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'right'
        });

        $("[data-step='0']").attr({
            'data-hint': "<p>" + this.localisedFile["step0"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(0)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(2)'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'top-left'
        });

        $("[data-step='3']").attr({
            'data-hint': "<p>" + this.localisedFile["step3"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(3)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(4)'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'right'
        });

        $("[data-step='4']").attr({
            'data-hint': "<p>" + this.localisedFile["step4"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(4)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(5)'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'right'
        });

        $("[data-step='5']").attr({
            'data-hint': "<p>" + this.localisedFile["step5"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(5)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(6)'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'right'
        });

        $("[data-step='6']").attr({
            'data-hint': "<p>" + this.localisedFile["step6"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(6)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(7)'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'right'
        });

        var nextStep = (roleId == 1) ? 8 : 1;
        $("[data-step='7']").attr({
            'data-hint': "<p>" + this.localisedFile["step7"] + "</p>" +
            "<a href='#' class='pull-left skip-tour' onclick='skip(7)'>" + this.localisedFile["skip"] + "</a>" +
            "<button class='pull-right nextBtn' onclick='goNext(" + nextStep + ")'>" + this.localisedFile["next"] + "</button>",
            'data-position': 'right'
        });

        if (roleId == 1) {
            $("[data-step='8']").attr({
                'data-hint': "<p>" + this.localisedFile["step8"] + "</p>" +
                "<a href='#' class='pull-left skip-tour' onclick='skip(8)'>" + this.localisedFile["skip"] + "</a>" +
                "<button class='pull-right nextBtn' onclick='goNext(1)'>" + this.localisedFile["next"] + "</button>",
                'data-position': 'right'
            });
        }

        $("[data-step='1']").attr({
            'data-hint': "<p>" + this.localisedFile["step1"] + "</p>" +
            "<button class='pull-left nextBtn closeBtn' onclick='skip()'>" + this.localisedFile["complete"] + "</button>",
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
        if (hintStatus == 0) {
            $('.introjs-hints').css('visibility', 'hidden');
        }

        if (completedTour == 0 && hintStatus == 1 && window.location.pathname == '/activity') {
            $("[data-step='0']").trigger('click');
        }
    },
    finalHints: function () {
        var intro = introJs();
        intro.setOptions({
            steps: [
                {
                    element: '#admin-dropdown',
                    intro: "<div>" +
                    "<p>" + this.localisedFile["final"] + "</p>" +
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
                    '<div class="intro-title text-center">' + this.localisedFile['publishingInfo1'] + '</div>' +
                    '<a id="btnInfo1" class="btn update-next">' + this.localisedFile['updateAndGo'] + '</a>' +
                    '<a id="info1" class="setup-later">' + this.localisedFile['setUpLater'] + '</a>',
                    position: 'bottom-middle-aligned'
                },
                {
                    element: '#publishing_info2',
                    intro: '' +
                    '<div class="intro-title text-center">' + this.localisedFile['publishingInfo2'] + '</div>' +
                    '<a id="btnInfo2" class="btn update-next">' + this.localisedFile['updateAndGo'] + '</a>' +
                    '<a id="info2" class="setup-later">' + this.localisedFile['setUpLater'] + '</a>',
                    position: 'bottom-middle-aligned'
                },
                {
                    element: '#publishing_info3',
                    intro: '' +
                    '<div class="intro-title text-center">' + this.localisedFile['publishingInfo3'] + '</div>' +
                    '<a id="btnInfo3" class="btn update-next">' + this.localisedFile['updateAndGo'] + '</a>' +
                    '<a id="info3" class="setup-later">' + this.localisedFile['setUpLater'] + '</a>',
                    position: 'top'
                },
                {
                    element: '#activity-elements-checklist-wrapper',
                    intro: '' +
                    '<div class="intro-title">' + this.localisedFile['activityElementsChecklist'] + '</div>' +
                    '<a id="btnInfo4" class="btn update-next">' + this.localisedFile['updateAndGo'] + '</a>' +
                    '<a id="info4" class="setup-later">' + this.localisedFile['setUpLater'] + '</a>',
                    position: 'bottom-middle-aligned'
                },
                {
                    element: '#default_values',
                    intro: '' +
                    '<div class="intro-title">' + this.localisedFile['defaultValues'] + '</div>' +
                    '<a id="btnInfo5" class="btn update-next">Finish set up process</a>' +
                    '<a id="info5" class="setup-later">' + this.localisedFile['setUpLater'] + '</a>',
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

        $('#default_currency', form).rules('add', {required: true, messages: {required: this.localisedFile['defaultCurrency']}});
        $('#default_language', form).rules('add', {required: true, messages: {required: this.localisedFile['defaultLanguage']}});
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

        $('#publisher_id', form).rules('add', {required: true, messages: {required: this.localisedFile['publisherId']}});
        $('#api_id', form).rules('add', {required: true, messages: {required: this.localisedFile['apiId']}});
    }
};