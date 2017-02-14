if (typeof(Chunk) == "undefined") var Chunk = {};

(function ($) {

    Chunk = {
        clickPagination: function (route) {
            $('.pagination a').click(function (e) {
                e.preventDefault();
                var pageNo = this.href.substr(this.href.indexOf('=') + 1);
                $('#form-filter').attr("action", route + pageNo);
                $('#form-filter').submit();
            });
        },
        submitFilter: function () {
            $('#form-filter').submit(function () {
                preventNavigation = false;
            });
        },
        toggleData: function (data) {
            $("#json-view").JSONView(data, {
                collapsed: true
            });

            $('#toggle-btn').on('click', function () {
                $('#json-view').JSONView('toggle');
            });
        },
        displayPicture: function () {
            function readURL(input) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#selected_picture').attr('src', e.target.result).parent('.uploaded-logo').addClass('has-image');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#picture").change(function () {
                readURL(this);
            });
        },
        usernameGenerator: function () {
            $('.username').on('change keyup', function (e) {
                var userIdentifier = $('#user-identifier').attr('data-id') + '_';
                var username = $(this).val();
                if (userIdentifier === username && (e.keyCode === 8 || e.keyCode === 46)) {
                    $(this).val('');
                } else if (userIdentifier.indexOf(username) === 0) {
                } else if (username.indexOf(userIdentifier) !== 0) {
                    $(this).val(userIdentifier + username);
                }
            });
        }, changeCountry: function () {
            var country = $('#country')
            country.change(function () {
                Chunk.filterAgency($(this).val());
            });
            var regAgency = $('#registration_agency').val();
            country.trigger('change');
            $('#registration_agency').val(regAgency).select2();
        },
        filterAgency: function (country) {
            var filteredAgencies = '<option value="" selected="selected">Select an Agency</option>';
            for (var i in agencies) {
                if (i.indexOf(country) == 0 || i.indexOf('XI') == 0 || i.indexOf('XM') == 0) {
                    filteredAgencies += '<option value="' + i + '">' + agencies[i] + '</option>';
                }
            }
            $('#registration_agency').html(filteredAgencies).select2();
        },
        updatePermission: function () {
            $('table tbody tr td').delegate('#permission', 'change', function (e) {
                var user_id = $(this).closest('tr').find('#user_id').val();
                var username = $(this).closest('tr').find('#name').html();
                var permission = $(this).val();
                var permission_text = $(':selected', this).text();
                $('#response').addClass('hidden')
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                    url: '/organization-user/update-permission/' + user_id,
                    data: {permission: permission},
                    type: 'POST',
                    beforeSend: function () {
                        $('body').append('<div class="loader">.....</div>');
                    },
                    complete: function () {
                        $('body > .loader').addClass('hidden').remove();
                    },
                    success: function (data) {
                        if (data == 'success') {
                            $('.alert-success').addClass('hidden');
                            $('#success').removeClass('hidden').html(permission_text + ' level permission has been given to ' + username);
                        } else {
                            $('.alert-danger').addClass('hidden');
                            $('#error').removeClass('hidden').html('Failed to update permission for ' + username);
                        }

                    }
                });
            });
        },
        verifyPublisherAndApi: function () {
            function verify(source) {
                var publisherId = $('#publisher_id').val();
                var apiKey = $('#api_id').val();
                var publisherIdStatus = $("#publisher_id_status_display");
                var apiIdStatus = $("#api_id_status_display");
                $('#error').addClass('hidden');
                if (shouldCheck(source, publisherId, apiKey, publisherIdStatus, apiIdStatus)) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                        url: '/publishing-settings/verifyPublisherAndApi',
                        data: {publisherId: publisherId, apiKey: apiKey},
                        type: 'POST',
                        beforeSend: function () {
                            $('body').append('<div class="loader">.....</div>');
                        },
                        complete: function () {
                            $('body > .loader').addClass('hidden').remove();
                        },
                        success: function (data) {
                            storeValues(source, data, publisherIdStatus, apiIdStatus);
                        }
                    });
                }
            }

            function shouldCheck(source, publisherId, apiKey, publisherIdStatus, apiIdStatus) {
                if ((source == "publisher" && publisherId != "") || (source == "api" && apiKey != "") || (apiIdStatus.html() == "Incorrect" || publisherIdStatus.html() == "Incorrect")) {
                    return true;
                }
            }

            function storeValues(source, data, publisherIdStatus, apiIdStatus) {
                var publisher_response = data['publisher_id'];
                var api_key = data['api_key'];
                var publisherStatus = (publisher_response) ? "Correct" : "Incorrect";
                var apiKeyStatus = (api_key) ? "Correct" : "Incorrect";
                $("[name = 'publisher_id_status']").val(publisherStatus);
                $("[name = 'api_id_status']").val(apiKeyStatus);

                if (source == "publisher" || source == "both") {
                    publisherIdStatus.val(publisherStatus);
                    $("#publisher_id_status_display").removeClass('text-danger text-success').addClass(publisher_response ? 'text-success' : 'text-danger').html(publisherStatus);
                }
                if (source == "api" || source == "both") {
                    apiIdStatus.val(apiKeyStatus);
                    $("#api_id_status_display").removeClass('text-danger text-success').addClass(api_key ? 'text-success' : 'text-danger').html(apiKeyStatus);
                }
            }

            $('#verify').on('click', function () {
                verify("both");
            });
            $('#publisher_id').on('blur', function () {
                verify("publisher");
            });
            $('#api_id').on('blur', function () {
                verify("api");
            });
        },
        checkImport: function () {
            var importCheckboxes = $('#import-activities input[type="checkbox"]:not([disabled="disabled"])');
            var submitBtn = $('#import-activities .btn_confirm');
            importCheckboxes.click(function () {
                submitBtn.attr('disabled', 'disabled');
                importCheckboxes.each(function () {
                    if ($(this).prop('checked')) {
                        submitBtn.removeAttr('disabled');
                        return true;
                    }
                });
            });
        },
        abbrGenerator: function () {
            // auto generates abbreviation from organization name
            var organisationName = $('.organisation_name').find('input').first();

            organisationName.on('change', function () {
                var name = $(this).val();
                var abbr = Chunk.getShortForm(name);
                $('.organization_name_abbr').val(abbr);
            });
        }, getShortForm: function (text) {
            var ignoreList = ['and', 'of', 'the', 'an', 'a'];

            function getWordList(text) {
                var nameArray = text.split(/\ +/g);
                return nameArray.filter(function (value) {
                    return ($.inArray(value.toLowerCase(), ignoreList) === -1 && value.length > 1);
                })
            }

            function getAbbr(wordList) {
                var abbr = '';
                for (var i in wordList) {
                    var word = wordList[i];
                    abbr += word.substr(0, 1);
                }
                return abbr.toLowerCase();
            }

            var wordList = getWordList(text);
            return getAbbr(wordList);
        }
    }
})
(jQuery);