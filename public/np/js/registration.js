var Registration = Registration || {};


function slash(value) {
    return value.replace(/([\[\]])/g, '\\$1');
}

(function ($) {
    var localisedData;
    Registration = {
        // ajax request handler
        callAsync: function (url, requestType) {
            return $.ajax({
                url: url,
                type: requestType
            })
        },
        localised: function () {
            this.callAsync('/localisedFormText', 'get').success(function (data) {
                localisedData = JSON.parse(data);
                Registration.startProcesses();
            });
        },
        startProcesses: function () {
            Registration.abbrGenerator();
            Registration.checkAbbrAvailability();
            Registration.changeCountry();
            Registration.regNumber();
            Registration.addRegAgency();
            Registration.addUser();
            Registration.removeUser();
            Registration.usernameGenerator();
            Registration.filterSimilarOrg();
            Registration.tabs();
        },
        request: function (url, data, callback, type) {
            type = type || 'POST';
            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                type: type,
                url: url,
                data: data,
                async: callback.async === undefined ? true : callback.async,
                success: function (data) {
                    if (typeof callback == 'function') callback(data);
                }
            });
        },
        // returns first letters from each words
        getShortForm: function (text) {
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
        },
        // auto generates abbreviation from organization name
        abbrGenerator: function () {
            $('.organization_name').change(function () {
                checkSimilarOrg = true;
                var name = $(this).val();
                var abbr = Registration.getShortForm(name);
                $('.organization_name_abbr').val(abbr).trigger('keydown').trigger('change').valid();
            });
        },
        // checks availability for abbreviation to be used
        checkAbbrAvailability: function () {
            var checkElem = $('.organization_name_abbr');
            checkElem.on('change', checkAvailability);
            checkElem.on('focus', function () {
                checkElem.parent().siblings('.availability-check').html('').addClass('hidden').removeClass('text-success text-danger');
            });

            checkElem.on('keydown', function () {
                checkElem.parent().siblings('.availability-check').html('').addClass('hidden').removeClass('text-success text-danger');
            });


            function checkAvailability() {
                var userIdentifier = $(this).val();

                if ($.trim(userIdentifier) == "") {
                    return false;
                }

                var containsSpaces = /\s/.test(userIdentifier);

                if (containsSpaces) {
                    checkElem.val("");
                    checkElem.next().html(localisedData['spaces_not_allowed']).css('display', 'block');
                    checkElem.parent().addClass('has-error');

                    return false;
                }

                var callback = function (data) {
                    checkElem.parent().siblings('.availability-check').siblings('.text-danger').remove();
                    checkElem.parent().siblings('.availability-check').removeClass('hidden text-danger test-success').addClass('text-' + data.status).html(data.message);
                    checkElem.parents('.has-error').removeClass('has-error');
                };
                Registration.request("/check-organization-user-identifier", {userIdentifier: checkElem.val()}, callback);
            }

            checkElem.trigger('change');
        },
        // filters registration agencies on country change
        changeCountry: function () {
            $('.country').change(function () {
                Registration.filterAgency($(this).val());
            });
            $('.country').trigger('change');
        },
        // filters registration agencies
        filterAgency: function (country) {
            var filteredAgencies = '<option value="" selected="selected">' + localisedData['select_an_agency'] + '</option>';
            var selected = '';
            for (var i in agencies) {
                if (i.indexOf(country) == 0 || i.indexOf('XI') == 0 || i.indexOf('XM') == 0) {
                    filteredAgencies += '<option value="' + i + '">' + agencies[i] + '</option>';
                    if (i == $('.agencies').attr('data-agency')) {
                        selected = i;
                    }
                }
            }
            $('.organization_registration_agency').html(filteredAgencies).val(selected);
        },
        // generates organization identifier with registration number
        regNumber: function () {
            $('.country, .organization_registration_agency, .registration_number, .registration_district').on('keyup change', function () {
                var identifier = '';
                if ($('.country').val() == '' || $('.organization_registration_agency').val() == '' || $('.registration_number').val() == '') {
                } else if($('.organization_registration_agency').val() == 'NP-DAO'){
                    identifier = $('.organization_registration_agency').val() + '-' + $('.registration_district').val() + '-' + $('.registration_number').val();    
                } else {
                    identifier = $('.organization_registration_agency').val() + '-' + $('.registration_number').val();
                }

                $('#org-identifier').html(identifier);
                $('.organization_identifier').val(identifier).trigger('blur');
            });
            $('.registration_number').trigger('change');
        },
        // generates username
        usernameGenerator: function () {
            $('.user-blocks').delegate('.username', 'change keyup', function (e) {
                var userIdentifier = $('.organization_name_abbr').val() + '_';
                var username = $(this).val();
                if (userIdentifier === username && (e.keyCode === 8 || e.keyCode === 46)) {
                    $(this).val('');
                } else if (userIdentifier.indexOf(username) === 0) {
                } else if (username.indexOf(userIdentifier) !== 0) {
                    $(this).val(userIdentifier + username);
                }
            });
        },
        // adds more user block
        addUser: function () {
            $('#add-user').click(function () {
                var index = 0;
                if ($('.user-blocks .user-block').length > 0) {
                    var name = $('.user-blocks .user-block:last-child .form-control:first').attr('name');
                    index = parseInt(name.match(/[\d]+/g)) + 1;
                }
                var template = $('#user_template').clone();
                var html = template.html();
                html = html.replace(/_index_/g, index);
                $('.user-blocks').append(html);
                $(this).html(localisedData['add_another_user']).prev('span').addClass('hidden');
                // Registration.disableUsersSubmitButton();
                Registration.usersValidationRules(index);
                bindTooltip();
                $('form select').select2();
            });
        },
        // removes user block
        removeUser: function () {
            $('.user-blocks').delegate('.delete', 'click', function (e) {
                e.preventDefault();
                var _this = $(this);

                if ($('#removeDialog').length === 0) {
                    $('body').append('' +
                        '<div class="modal" id="removeDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<h4 class="modal-title" id="myModalLabel"></h4>' +
                        '</div>' +
                        '<div class="modal-body"></div>' +
                        '<div class="modal-footer"></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                }

                var removeDialog = $('#removeDialog');

                var buttons = '' +
                    '<button class="btn btn-primary btn_remove" type="button">' + localisedData['yes'] + '</button>' +
                    '<button class="btn btn-default" type="button"  data-dismiss="modal">' + localisedData['no'] + '</button>';

                $('.modal-header .modal-title', removeDialog).html(localisedData['remove_confirmation']);
                $('.modal-body', removeDialog).html(localisedData['remove_block']);
                $('.modal-footer', removeDialog).html(buttons);

                $('body').undelegate('.btn_remove', 'click').delegate('.btn_remove', 'click', function () {
                    _this.parent('.user-block').remove();
                    if ($('.user-blocks .user-block').length == 0) {
                        $('#add-user').html(localisedData['add_additional_users']).prev('span').removeClass('hidden');
                    }
                    removeDialog.modal('hide');
                    Registration.disableUsersSubmitButton();
                });

                removeDialog.modal('show');
            });
        },
        // disables organization submit button
        disableOrgSubmitButton: function () {
            var fieldList = [
                '#organization_name',
                '#organization_name_abbr',
                '#organization_type',
                '#organization_address',
                '#country',
                '#organization_registration_agency',
                '#registration_number',
                '#organization_identifier'
            ];
            Registration.disableSubmit(fieldList, '#organization-form');
        },
        // disables users submit button
        disableUsersSubmitButton: function () {
            var fieldList = [
                '#first_name',
                '#last_name',
                '#email',
                '#password',
                '#confirm_password',
                '#secondary_contact',
                '[name$="[username]"]',
                '[name$="[email]"]',
                '[name$="[first_name]"]',
                '[name$="[last_name]"]',
                '[name$="[role]"]'
            ];
            Registration.disableSubmit(fieldList, '#users-form');
        },
        // disables submit button of given form
        disableSubmit: function (fieldList, form) {
            var fields = $(fieldList.join(', '), form);
            fields.on('change', function () {
                var check = true;
                fields.each(function () {
                    if ($(this).val() == "") {
                        check = false;
                        return false;
                    }
                });
                if (check) {
                    $('button[type="submit"]', form).removeAttr('disabled');
                } else {
                    $('button[type="submit"]', form).attr('disabled', 'disabled');
                }
            });
            fields.eq(0).trigger('change');
        },
        // lists similar organizations
        filterSimilarOrg: function () {
            $('.search_org').keydown(function (e) {
                if (e.keyCode === 13) {
                    $('.btn-search').trigger('click');
                    return false;
                }
            });

            $('.btn-search').click(function () {
                $(".organization-list").jScrollPane().data().jsp.destroy();
                var value = $('.search_org').val();
                if (value != '') {
                    $('body').append('<div class="loader">.....</div>');
                    $.ajax({
                        type: 'get',
                        url: '/similar-organizations/' + value,
                        success: function (data) {
                            var list = '';
                            for (var i in data) {
                                list += '<li><a data-value="' + i + '" title="' + data[i] + '">' + data[i] + '</a></li>';
                            }
                            if (list == '') {
                                $('.org-list-container').addClass('hidden').find('ul').html('');
                                $('.no-org-list').removeClass('hidden');
                            } else {
                                $('.org-list-container').removeClass('hidden').find('ul').html(list);
                                $('.no-org-list').addClass('hidden');
                            }
                            $(".organization-list").jScrollPane();
                        },
                        complete: function () {
                            $('body > .loader').addClass('hidden').remove();
                        }
                    });
                } else {
                    $('.org-list-container').addClass('hidden').find('ul').html('');
                    $(".organization-list").jScrollPane();
                }
            });

            $('#similar-org-modal').on('shown.bs.modal', function () {
                $('.search_org').val($(slash('#organization[organization_name]')).val());
                $('.btn-search').trigger('click');
            });

            $('#similar-org-form').submit(function (e) {
                var type = $('[name="type"]', this).val();
                var orgId = $('[name="similar_organization"]', this).val();
                if (!orgId) {
                    e.preventDefault();
                    $('#similar-org-modal').modal('hide');
                    checkSimilarOrg = false;
                    $('a[href="#tab-users"]').tab('show');
                }
            });

            $('.btn-back').click(function () {
                $('.similar-org-container').removeClass('hidden');
                $('.similar-org-action').addClass('hidden');
            });

            $('.clickable-org').delegate('a', 'click', function () {
                $('[name="similar_organization"]').val($(this).attr('data-value'));
                $('#similar-org-form').submit();
            });

            // forgot password page
            $('.btn-type').click(function () {
                var modal = $('#similar-org-modal');
                $('[name="type"]', modal).val($(this).attr('data-type'));
                modal.modal('show');
            });
        },
        // addition of registration agency
        addRegAgency: function () {
            var modal = $('#reg_agency');
            $('.add_agency').click(function () {
                modal.modal('show');
            });

            modal.on('show.bs.modal', function () {
                var country = $('.country').val();
                if (country == "") {
                    $('button[type="submit"]', this).addClass('hidden');
                    $('.messages', modal).removeClass('hidden').html(localisedData['select_a_country']);
                } else {
                    $('.form-container', modal).removeClass('hidden');
                    $('button[type="submit"]', this).removeClass('hidden');
                }
            });

            modal.on('hidden.bs.modal', function () {
                $('.messages, .form-container', '#reg_agency').addClass('hidden');
            });

            $.validator.addMethod("abbr", function (value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                return /^[A-Z]+$/.test(value);
            });

            $.validator.addMethod("abbr_exists", function (value, element, param) {
                var regAgency = $('.country').val() + '-' + value;
                return !(regAgency in agencies);
            });

            var form = $('#reg-agency-form');

            $('#name', form).change(function () {
                var abbr = Registration.getShortForm($(this).val());
                $('#short_form', form).val(abbr.toUpperCase());
            });

            $('#short_form', form).keyup(function () {
                $(this).val($(this).val().toUpperCase());
            });

            form.validate({
                submitHandler: function () {
                    var country = $('.country').val();
                    var name = $('#name', form).val();
                    var shortForm = $('#short_form', form).val();
                    var website = $('#website', form).val();
                    var regAgency = $('.organization_registration_agency');
                    var agencyData = JSON.parse($('.agencies').val());
                    var agencyCode = country + '-' + shortForm;
                    agencyData[agencyCode] = name;
                    agencies = agencyData;
                    $('#agency_name').val(name);
                    $('#agency_website').val(website);
                    $('.agencies').val(JSON.stringify(agencyData));
                    modal.modal('hide');
                    $('.country').trigger('change');
                    regAgency.val(agencyCode).trigger('change');

                    var newAgencies = $('.new_agencies').val();
                    newAgencies = newAgencies == '' ? {} : JSON.parse(newAgencies);
                    newAgencies[agencyCode] = {name: name, short_form: shortForm, website: website};
                    $('.new_agencies').val(JSON.stringify(newAgencies));
                },
                errorClass: 'text-danger',
                highlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').eq(0).addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').eq(0).removeClass('has-error');
                },
                invalidHandler: function () {
                    $('button[type="submit"]', form).removeAttr('disabled');
                }
            });
            form.submit(function () {
                $('button[type="submit"]', this).removeAttr('disabled');
            });
            $('#name', form).rules('add', {required: true, messages: {required: localisedData['name_required']}});
            $('#short_form', form).rules('add', {
                required: true,
                abbr: true,
                abbr_exists: true,
                messages: {
                    required: localisedData['short_form_required'],
                    abbr: localisedData['short_form_alphabetic'],
                    abbr_exists: localisedData['registration_agency_exists']
                }
            });
            $('#website', form).rules('add', {
                required: true, url: true, messages: {
                    required: localisedData['website_required'], url: localisedData['website_not_url']
                }
            });

        },
        // validations for registration form
        validation: function () {
            $.validator.addMethod("email", function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+\@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])\.[.a-zA-Z0-9](?:[.a-zA-Z0-9-]{0,61}[a-zA-Z0-9])*$/.test($.trim(value));
            });
            $.validator.addMethod("regNumber", function (value, element) {
                return this.optional(element) || /^[0-9a-zA-Z-_]+$/.test($.trim(value));
            });
            $.validator.addMethod("uniqueAbbr", function (value, element) {
                var validated = false;
                var callback = function (data) {
                    if (data) {
                        validated = true;
                    }
                };
                callback.async = false;
                Registration.request("/check-organization-user-identifier", {userIdentifier: value, validation: true}, callback);
                return this.optional(element) || validated;
            });

            var form = $('#from-registration');
            var validation = form.validate({
                errorClass: 'text-danger',
                highlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').eq(0).addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').eq(0).removeClass('has-error');
                },
                invalidHandler: function () {
                    $('button[type="submit"]', form).removeAttr('disabled');
                }
            });
            form.submit(function () {
                preventNavigation = false;
            });

            /* organization validation rules */
            $(slash('#organization[organization_name]'), form).rules('add', {required: true, messages: {required: localisedData['org_name_required']}});
            $(slash('#organization[organization_name]'), form).rules('add', {maxlength: 100, messages: {maxlength: localisedData['org_name_maxlength']}});
            $(slash('#organization[organization_name_abbr]'), form).rules('add', {required: true, messages: {required: localisedData['org_name_abbrev_required']}});
            $(slash('#organization[organization_type]'), form).rules('add', {required: true, messages: {required: localisedData['org_type_required']}});
            $(slash('#organization[organization_address]'), form).rules('add', {required: true, messages: {required: localisedData['address_required']}});
            $(slash('#organization[country]'), form).rules('add', {required: true, messages: {required: localisedData['country_required']}});
            $(slash('#organization[organization_district]'), form).rules('add', {required: true, messages: {required: localisedData['district_required']}});
            $(slash('#organization[organization_municipality]'), form).rules('add', {required: true, messages: {required: localisedData['municipality_required']}});
            $(slash('#organization[organization_registration_agency]'), form).rules('add', {required: true, messages: {required: localisedData['org_registration_agency_required']}});
            $(slash('#organization[registration_number]'), form).rules('add', {
                required: true,
                regNumber: true,
                messages: {required: localisedData['registration_number_required'], regNumber: localisedData['letters_and_numbers_allowed']}
            });
            $(slash('#organization[organization_identifier]'), form).rules('add', {required: true, messages: {required: localisedData['iati_organisational_identifier_required']}});

            /* users validation rules */
            $(slash('#users[first_name]'), form).rules('add', {required: true, messages: {required: localisedData['first_name_required']}});
            $(slash('#users[last_name]'), form).rules('add', {required: true, messages: {required: localisedData['last_name_required']}});
            $(slash('#users[email]'), form).rules('add', {required: true, email: true, messages: {required: localisedData['email_required']}});
            $(slash('#users[password]'), form).rules('add', {required: true, minlength: 6, messages: {required: localisedData['password_required']}});
            $(slash('#users[confirm_password]'), form).rules('add', {required: true, equalTo: $(slash('#users[password]'), form), messages: {required: localisedData['confirm_password_required']}});
            /*$(slash('#users[secondary_contact]'), form).rules('add', {required: true, email: true, messages: {required: localisedData['secondary_contact_required']}});*/
            $('.user-blocks .user-block').each(function () {
                Registration.usersValidationRules($(this).index());
            });

            return validation;
        },
        // user validations
        usersValidationRules: function (index) {
            var form = $('#from-registration');
            $(slash('#users[user][' + index + '][username]'), form).rules('add', {required: true, messages: {required: localisedData['username_required']}});
            $(slash('#users[user][' + index + '][email]'), form).rules('add', {required: true, email: true, messages: {required: localisedData['email_address_required']}});
            $(slash('#users[user][' + index + '][first_name]'), form).rules('add', {required: true, messages: {required: localisedData['first_name_required']}});
            $(slash('#users[user][' + index + '][last_name]'), form).rules('add', {required: true, messages: {required: localisedData['last_name_required']}});
            $(slash('#users[user][' + index + '][role]'), form).rules('add', {required: true, messages: {required: localisedData['permission_role_required']}});
        },
        // handles registration tabs
        tabs: function () {
            $('.preventClose').modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });

            if ($('.organization_name_abbr').parents('.form-group').eq(0).hasClass('has-error')) {
                $('.organization_name_abbr').trigger('change');
            }

            $('#from-registration input').keydown(function (e) {
                if (e.keyCode === 13) {
                    var nextTab = $(this).parents('.tab-pane').next('.tab-pane').attr('id');
                    if (nextTab != undefined && nextTab != 'tab-verification') {
                        $('a[href="#' + nextTab + '"]').tab('show');
                        return false;
                    }
                }
            });

            var firstInvalidElem = $('.form-group.has-error').eq(0).find('.form-control');
            var tabId = firstInvalidElem.parents('.tab-pane').eq(0).attr('id');
            if (tabId != undefined) {
                $('a[href="#' + tabId + '"]').tab('show');
                firstInvalidElem.focus().trigger("focusin");
            }

            var validation = Registration.validation();
            $('[data-tab-trigger]').click(function () {
                $('a[href="' + $(this).attr('data-tab-trigger') + '"]').trigger('click');
            });
            $('[data-toggle="tab"]').click(function () {
                if (!$(this).is('.disabled')) {
                    $(this).tab('show');
                }
                return false;
            });
            $('a[data-toggle="tab"]').on('hide.bs.tab', function (e) {
                var currentTab = $(e.target);
                var nextTab = $(e.relatedTarget);
                if (currentTab.parent('li').index() < nextTab.parent('li').index()) {
                    try {
                        $(slash('#organization[organization_name_abbr]'), '#from-registration').rules('add', {
                            uniqueAbbr: true,
                            messages: {uniqueAbbr: localisedData['org_name_abbrev_taken']}
                        });
                    } catch (e) {
                    }
                    var isValid = $('input, select', '#from-registration').valid();
                    $(slash('#organization[organization_name_abbr]'), '#from-registration').rules('remove', 'uniqueAbbr');
                    if (!isValid) {
                        validation.focusInvalid();
                        return false;
                    }
                    if (currentTab.attr('href') == '#tab-organization' && nextTab.attr('href') != '#tab-verification') {
                        setIdentifier();
                        if (!(Registration.verifyOrgIdentifier() && Registration.verifySimilarOrgs())) {
                            return false;
                        }
                    }
                }
                $('.nav-tabs li').removeClass('complete');
                nextTab.parent('li').prevAll().addClass('complete');
            });
            function setIdentifier() {
                $('#username').val($('.organization_name_abbr').val() + '_admin');
            }

            setIdentifier();
        },
        verifyOrgIdentifier: function () {
            var orgIdentifier = $('.organization_identifier').val();
            var response = Registration.checkOrgIdentifier(orgIdentifier);
            if (!$.isEmptyObject(response)) {
                var modal = $('#org-identifier-modal');
                $('.org-identifier', modal).html(orgIdentifier);
                $('.org-name', modal).html(response.org_name);
                $('.admin-name', modal).html(response.admin_name);
                modal.modal('show');
                return false;
            }
            return true;
        },
        checkOrgIdentifier: function (orgIdentifier) {
            if ($.trim(orgIdentifier) == '') {
                return [];
            }
            return Registration.request("/check-org-identifier", {org_identifier: orgIdentifier}, {async: false}).responseJSON;
        },
        verifySimilarOrgs: function () {
            if (checkSimilarOrg && !$.isEmptyObject(Registration.checkSimilarOrgs())) {
                $('#similar-org-modal').modal('show');
                return false;
            }
            return true;
        },
        checkSimilarOrgs: function () {
            var orgName = $('.organization_name').val();
            if ($.trim(orgName) == "") {
                return [];
            }
            return Registration.request('/similar-organizations/' + orgName, {}, {async: false}, 'GET').responseJSON;
        },
        // checkOrgIdentifier: function () {
        //     var orgIdentifier = $('.organization_identifier').val();
        //     if (orgIdentifier == '') {
        //         return [];
        //     }
        //     var callback = function (data) {
        //         if (!$.isEmptyObject(data)) {
        //             Registration.orgData = data;
        //             $('a[href="#tab-organization"]').tab('show');
        //             $('#org-identifier-modal').modal('show');
        //         }
        //     };
        //     callback.async = false;
        //     return Registration.request("/check-org-identifier", {org_identifier: orgIdentifier}, callback).responseJSON;
        // },
        // handles same organization identifier
        // sameIdentifier: function () {
        //     $('.preventClose').modal({
        //         backdrop: 'static',
        //         keyboard: false,
        //         show: false
        //     });
        //     $('#org-identifier-modal').on('show.bs.modal', function () {
        //         preventNavigation = false;
        //         $('.org-name').html(Registration.orgData.org_name);
        //         $('.admin-name').html(Registration.orgData.admin_name);
        //     });
        //
        //     $('.confirm-organization').click(function () {
        //         $('#org-identifier-modal').modal('hide');
        //         $('#org-identifier-confirmation-modal').modal('show');
        //     });
        //
        //     $('.need-new-user').click(function () {
        //         $('#org-identifier-confirmation-modal').modal('hide');
        //         $('#contact-admin-modal').modal('show');
        //     });
        // },
        // sameIdentifier: function () {
        //     $('[data-section]').click(function () {
        //         var sectionId = $(this).attr('data-section');
        //         $(sectionId).removeClass('hidden').siblings('.section').addClass('hidden');
        //     });
        // },
        // handles similar organizations
        similarOrgs: function () {
            var checkElem = $('.organization_name');
            checkElem.on('change', checkAvailability);
            checkElem.on('keydown', function () {
                checkElem.parent().siblings('.availability-check').html('').addClass('hidden').removeClass('text-warning');
            });
            function checkAvailability() {
                var orgName = $(this).val();
                if ($.trim(orgName) == "") {
                    return false;
                }
                var callback = function (data) {
                    if ($.isEmptyObject(data)) {
                        checkElem.parent().siblings('.availability-check').html('').addClass('hidden').removeClass('text-warning');
                    } else {
                        checkElem.parent().siblings('.availability-check').html(localisedData['account_with_similar_name']).removeClass('hidden').addClass('text-warning');
                    }
                };
                Registration.request('/similar-organizations/' + orgName, {}, callback, 'GET');
            }

            var form = $('#from-registration');

            form.delegate('.check_similar_org', 'click', function (e) {
                e.preventDefault();
                form.attr('action', '/find-similar-organizations');
                form.validate().destroy();
                form.submit();
            });
        },
        showValidation: function () {
            $('[href="#tab-organization"], [href="#tab-users"]').addClass('disabled').parent('li').addClass('complete');
            $('#from-registration').validate().destroy();
            $('a[href="#tab-verification"]').removeClass('disabled').tab('show');
        }
    }

})(jQuery);
