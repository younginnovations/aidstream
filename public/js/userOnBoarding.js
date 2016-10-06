(function ($) {
    UserOnBoarding = {
        dashboardTour: function () {
            var intro = introJs();
            var step6Text = (roleId == 1) ? 'View Activity log' : 'My Profile';
            intro.setOptions({
                steps: [
                    {
                        element: '#step-1',
                        intro: "" +
                        "Click here to view the list of Activities you have added" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn1'>Next: Add an Activity</a></div>",
                        position: 'right'
                    },
                    {
                        element: '#step-2',
                        intro: "" +
                        "Hover over here to get options to add an activity" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn2'>Next: View Organisation data</a></div>",
                        position: 'bottom'
                    },
                    {
                        element: '#step-3',
                        intro: "" +
                        "Click here to view your organisation's which you can publish/update to the IATI Registry" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn3'>Next: View documents</a></div>",
                        position: 'right'
                    },
                    {
                        element: '#step-4',
                        intro: "" +
                        "Click here to view the documents you have added under document link" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn4'>Next: Download your data</a></div>",
                        position: 'right'
                    },
                    {
                        element: '#step-5',
                        intro: "" +
                        "Click here to get options to download your data which is on AidStream" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn5'>Next: View your organisation's settings</a></div>",
                        position: 'right'
                    },
                    {
                        element: '#step-6',
                        intro: "" +
                        "Click here to view your organisation's account settings. Users with Administrator level permission can edit settings from here" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn6'>Next:" + step6Text + " </a> </div> ",
                        position: 'right'
                    },
                    {
                        element: '#step-7',
                        intro: "" +
                        "Click here to view the changes made by users" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn7'>Next: My Profile</a></div>",
                        position: 'right'
                    },
                    {
                        element: '#step-8',
                        intro: "" +
                        "Hover over here and click on “My Profile” to go your profile page" +
                        "<div><a href='/activity'>Skip</a></div>" +
                        "<div><a href='#' id='btn8'>Go to dashboard</a></div>",
                        position: 'bottom'
                    }
                ], exitOnOverlayClick: false,
                showStepNumbers: false,
                showButtons: false,
                showBullets: false

            });
            intro.start();

            $('body').delegate('#btn1', 'click', function () {
                intro.goToStep(2);
            }).delegate('#btn2', 'click', function () {
                intro.goToStep(3);
            }).delegate('#btn3', 'click', function () {
                intro.goToStep(4);
            }).delegate('#btn4', 'click', function () {
                intro.goToStep(5);
            }).delegate('#btn5', 'click', function () {
                intro.goToStep(6);
            }).delegate('#btn6', 'click', function () {
                (roleId == 1) ? intro.goToStep(7) : intro.goToStep(8);
            }).delegate('#btn7', 'click', function () {
                intro.goToStep(8);
            }).delegate('#btn8', 'click', function () {
                window.location.href = '/activity'
            });
        },
        settingsTour: function () {
            var intro = introJs();

            intro.setOptions({
                steps: [
                    {
                        element: '#publishing_info1',
                        intro: '' +
                        '<div><button id="btnInfo1">Update and go to next step</button></div>' +
                        '<div><a id="info1">I&rsquo;ll set this up later</a></div>',
                        position: 'bottom-right-aligned'
                    },
                    {
                        element: '#publishing_info2',
                        intro: '' +
                        '<div><button id="btnInfo2">Update and go to next step</button></div>' +
                        '<div><a href="#" id="info2">I&rsquo;ll set this up later</a></div>',
                        position: 'bottom-right-aligned'
                    },
                    {
                        element: '#publishing_info3',
                        intro: '' +
                        '<div><button id="btnInfo3">Update and go to next step</button></div>' +
                        '<div><a href="#" id="info3">I&rsquo;ll set this up later</a></div>',
                        position: 'bottom-right-aligned'
                    },
                    {
                        element: '#activity-elements-checklist',
                        intro: '' +
                        '<div><button id="btnInfo4">Update and go to to next step</button></div>' +
                        '<div><a href="#" id="info4">I&rsquo;ll set this up later</a></div>',
                        position: 'bottom-right-aligned'
                    },
                    {
                        element: '#default_values',
                        intro: '' +
                        '<div><button id="btnInfo5">Finish set up process</button></div>' +
                        '<div><a href="#" id="info5">I&rsquo;ll set this up later</a></div>',
                        position: 'bottom-right-aligned'
                    }
                ],
                exitOnOverlayClick: false,
                showStepNumbers: false,
                showButtons: false,
                exitOnEsc: false
            });

            intro.start();

            var links = {
                '1': '/publishing-settings/#1',
                '2': '/publishing-settings/#2',
                '3': '/publishing-settings/#3',
                '4': '/activity-elements-checklist/#4',
                '5': '/default-values/#5'
            };

            var steps = $('.introjs-bullets a').each(function () {
                var stepNumber = $(this).attr('data-stepnumber');
                $(this).attr('href', links[stepNumber]);
            });

            var stepNumber = location.hash.replace('#', '')
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
        },
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
    }
})
(jQuery);