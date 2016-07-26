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
                $('#error').addClass('hidden');
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
                        var publisher_response = data['publisher_id'];
                        var api_key = data['api_key'];
                        publisher_response = (publisher_response) ? "Verified" : "Not Verified";
                        api_key = (api_key) ? "Correct" : "Incorrect";
                        if (source == "publisher") {
                            $("#publisher_id_status").val(publisher_response);
                        } else if (source == "api") {
                            $("#api_id_status").val(api_key);
                        } else {
                            $("#publisher_id_status").val(publisher_response);
                            $("#api_id_status").val(api_key);
                        }
                    }
                });
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
        }
    }
})
(jQuery);