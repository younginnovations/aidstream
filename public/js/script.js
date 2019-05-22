var preventNavigation = false;
var localisedData;

var callAsync = function (url, requestType) {
    return $.ajax({
        url: url,
        type: requestType
    })
};

callAsync('/localisedFormText', 'get').success(function (data) {
    localisedData = JSON.parse(data);
});


/* Add tooltip */
function bindTooltip() {
    $('[data-toggle="tooltip"]').tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function (position, feedback) {
                $(this).css(position);
                $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .css({left: feedback.target.left - position.left})
                    .appendTo(this);
            }
        },
        hide: 'hide'
    });
}

$(document).ready(function () {

    var removedAll = false;

    /* Convert laravel form builder's form prototype to custom form template */
    if ($('.collection-container').length > 0) {
        var proto = $('.collection-container').attr('data-prototype');
        var result = proto.replace(
            /"([\w]+)\[__NAME__\][\w\[\]]+"/g,
            function () {
                var value = arguments[0];
                var length = value.search('[__NAME__]');
                for (var i = 0; i < length; i++) {
                    value = value.replace('[__NAME__]', '[__NAME' + i + '__]');
                }
                return value;
            }
        );
        $('.collection-container').removeAttr('data-prototype').append(result);
    }

    bindTooltip();

    /* Add form on click to Add More button */
    $('form').delegate('.add_to_collection', 'click', function () {
        var collection = $(this).attr('data-collection');
        var container = $(this).prev('.collection_form');
        var parents = $(this).parents('.collection_form');
        var level = parents.length;
        var indexString = $(' > .form-group:last .form-control', container).eq(0).attr('name');
        if (indexString === undefined) {
            indexString = '';
        }

        var matchedIndexes = indexString.match(/[\d]+/g);
        var parentIndexes = [];
        var newIndex = 0;
        if (matchedIndexes) {
            parentIndexes = matchedIndexes.map(function (i) {
                return parseInt(i);
            });
            newIndex = parentIndexes[level] + 1;
        }
        if (removedAll === true) {
            removedAll = false;
            $(' > .form-group', container).remove();
            newIndex--;
        }
        var protoHtml = level === 0 ? $('.collection-container') : $('.' + collection, '.collection-container');
        protoHtml = protoHtml.clone();
        $('[type="date"]', protoHtml).attr('type', 'text').addClass('datepicker')
        $('option[selected="selected"]', protoHtml).removeAttr('selected');
        protoHtml.children('.form-group').addClass('added-new-block');
        protoHtml.children('label').remove();
        var proto = protoHtml.html();
        for (var i = 0; i < level; i++) {
            var parentIndex = parentIndexes[i];
            proto = proto.replace(
                new RegExp('__NAME' + i + '__', 'g'),
                parentIndex ? parentIndex : 0
            );
        }
        proto = proto.replace(new RegExp('__NAME' + level + '__', 'g'), newIndex);
        proto = proto.replace(/__NAME[\d]+__/g, 0);
        if ($(' > .form-group:last', container).length > 0) {
            $(' > .form-group:last', container).after(proto);
        } else if ($(' > .text-danger:first', container).length > 0) {
            $(' > .text-danger:first', container).before(proto);
        } else {
            $(container).append(proto);
        }
        addDatepicker();
        bindTooltip();
        $('form select').select2();
        if (typeof addMoreCallback == 'function') {
            addMoreCallback();
        }
    });

    /* remove html5 validation and scroll to first invalid field */
    $('form').attr('novalidate', 'novalidate');
    if ($('form .form-group.has-error').eq(0).length > 0) {
        $(document).scrollTop($('form .form-group.has-error').eq(0).offset().top - $('.navbar-default').eq(0).height());
    }

    /* Removes form on click to Remove This button */
    $('form').delegate('.remove_from_collection', 'click', function () {
        var _this = $(this);
        var sth = _this.parent().find('.map_container').attr('id');

        if ($('#removeDialog').length === 0) {
            $('body').append('' +
                '<div class="modal" id="removeDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999" data-source="' + sth + '">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
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
            var parents = _this.parents('.collection_form');
            var collectionForm = parents.eq(0);
            if ($('> .form-group', collectionForm).length === 1) {
                removedAll = true;
                collectionForm.next('.add_to_collection').trigger('click');
            } else {
                _this.parent('.form-group').remove();
            }
            removeDialog.modal('hide');

            /* reset indexes */
            var level = parents.length - 1;
            var indexString = $(' > .form-group:last .form-control', collectionForm).eq(0).attr('name');
            if (indexString === undefined) {
                indexString = '';
            }

            var bracketIndexes = [0];
            while ((bracketIndex = indexString.indexOf('[', bracketIndexes.slice(-1)[0] + 1)) != -1) {
                bracketIndexes.push(bracketIndex);
            }

            var currentBracketIndex = bracketIndexes[((level * 2) + 1)];
            var stringUpToBracket = indexString.substring(0, currentBracketIndex + 1);

            var fieldNames = [];
            $('[name^="' + stringUpToBracket + '"]').each(function (a, b) {
                var fieldName = $(b).attr('name');
                var replaceValue = fieldName.substring(0, fieldName.indexOf(']', currentBracketIndex));
                fieldNames.push(replaceValue);
            });
            fieldNames = $.unique(fieldNames);

            for (var i = 0; i < fieldNames.length; i++) {
                var fieldName = fieldNames[i] + ']';
                var fields = $('[name^="' + fieldName + '"]');
                var labels = $('[for^="' + fieldName + '"]');
                var pattern = new RegExp('(' + stringUpToBracket.replace(/\[/g, '\\[').replace(/\]]/g, '\\]') + ')' + '([\\d]+)' + '([^.]+)', 'g');
                fields.each(function () {
                    var field = $(this);
                    var replaceWith = field.attr('name').replace(pattern, '$1' + i + '$3');
                    field.attr({'name': replaceWith, 'id': replaceWith});
                });
                labels.each(function () {
                    var label = $(this);
                    var replaceWith = label.attr('for').replace(pattern, '$1' + i + '$3');
                    var replaceWith = label.attr('for').replace(pattern, '$1' + i + '$3');
                    label.attr({'for': replaceWith});
                });
            }
        });

        removeDialog.modal('show');
    });

    /* change application language */
    var language = $.cookie('language');
    language = language ? language : 'en';
    $('.language-select-wrap .flag-icon-' + language).parent().addClass('active');
    $('.language-select-wrap .flag-wrapper').click(function () {
        $.cookie('language', $(this).attr('data-lang'), {path: '/'});
        window.location.reload();
    });

    /* auto-generate username */
    $('input[name="organization_user_identifier"]').on('keyup change', function () {
        var username = '';
        if ($(this).val() == "") {
            $('.username_text').removeClass('hidden');
            $('.username_value').addClass('hidden');
        } else {
            username = $(this).val() + '_admin';
            $('.username_text').addClass('hidden');
            $('.username_value').removeClass('hidden');
        }
        $('input[name="username"]').val(username);
        $('.alternate_input').html(username);
    });
    if ($('input[name="organization_user_identifier"]').val() !== '') {
        $('input[name="organization_user_identifier"]').trigger('keyup');
    }

    /* auto-generate iati activity identifier */
    $('input[name="activity_identifier"]').on('keyup change', function () {
        var iatiIdentifier = '';
        if ($(this).val() == "") {
            $('.identifier_text').removeClass('hidden');
            $('.iati_identifier_text').addClass('hidden');
        } else {
            iatiIdentifier = $('#reporting_organization_identifier').text() + '-' + $(this).val();
            $('.identifier_text').addClass('hidden');
            $('.iati_identifier_text').removeClass('hidden');
        }
        $('input[name="iati_identifier_text"]').val(iatiIdentifier);
        $('.alternate_input').html(iatiIdentifier);
    });
    if ($('input[name="activity_identifier"]').val() !== '') {
        $('input[name="activity_identifier"]').trigger('keyup');
    }

    /* show/hide tooltip on hover/out */
    $('.hover_help_text').hover(function () {
        $(this).next('.help-text').trigger('mouseover');
    }, function () {
        $(this).next('.help-text').trigger('mouseout');
    });

    /* check/uncheck all check boxes with attribute readonly */
    $('.checkAll').click(function () {
        var checked = this.checked;
        $('.field1').not('[readonly="readonly"]').prop('checked', checked);
        $('.check-text').text(checked ? 'Uncheck All' : 'Check All');
    });

    /* prevent check/uncheck of readonly checkboxes */
    $('input[type="checkbox"][readonly="readonly"]').change(function () {
        $(this).prop('checked', !this.checked);
    });

    /* prevent selection for readonly select fields */
    $('form').delegate('select[readonly=readonly]', 'mousedown', function (e) {
        e.preventDefault();
    });

    /*
     * Confirmation for form submission
     * Usage:
     * Define form id
     * Define Submit button params as:
     *   type = "button"
     *   class = "btn_confirm"
     *   data-title = "confirmation title" (optional)
     *   data-message = "confirmation message"
     * */
    $('.btn_confirm').click(function () {

        var title = $(this).attr('data-title');
        var message = $(this).attr('data-message');
        var formId = $(this).parents('form').attr('id');

        if ($('#popDialog').length === 0) {
            $('body').append('' +
                '<div class="modal" id="popDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">' +
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

        var popElem = $('#popDialog');

        if (title === undefined) {
            $('.modal-header', popElem).addClass('hidden').children('.modal-title').html('');
        }
        else {
            $('.modal-header', popElem).removeClass('hidden').children('.modal-title').html(title);
        }

        $('.modal-body', popElem).html(message);

        var buttons = '' +
            '<button class="btn btn-primary btn_yes" type="button">Yes</button>' +
            '<button class="btn btn-default" type="button"  data-dismiss="modal">No</button>';

        $('.modal-footer', popElem).html(buttons);

        $('body').undelegate('.btn_yes', 'click').delegate('.btn_yes', 'click', function () {
            preventNavigation = false;
            $('#' + formId).submit();
        });

        popElem.modal('show');
    });

    /* change the sector field according to the  sector vocabulary selected */
    $("form").delegate('.sector_vocabulary', 'change', function () {
        var parent = $(this).parent('.form-group');
        var vocabulary = $(this).val();
        if (vocabulary == '') {
            vocabulary = 1;
        }
        var sectorClass = ['.sector_text', '.sector_select', '.sector_category_select'];
        var selectedSector = sectorClass[vocabulary] ? sectorClass[vocabulary] : sectorClass[0];
        parent.siblings('.sector_types').addClass('hidden').children('.form-control').removeAttr('required');
        parent.siblings(selectedSector).removeClass('hidden').children('.form-control').attr('required', 'required');
    });
    $('.sector_vocabulary').trigger('change');

    /* change the Default Aid Type Code field according to the Default AidType vocabulary selected */
    $("form").delegate('.default_aidtype_vocabulary', 'change', function () {
        var parent = $(this).parent('.form-group');
        var aidtypeVocabulary = $(this).val();
        if (aidtypeVocabulary == '') {
            aidtypeVocabulary = 1;
        }
        var aidtypeClass = ['','.aidtype_select', '.aidtype_earmarking_category', '.aidtype_text'];
        var selectedAidtype = aidtypeClass[aidtypeVocabulary] ? aidtypeClass[aidtypeVocabulary] : aidtypeClass[0];
        parent.siblings('.default_aidtypes').addClass('hidden').children('.form-control').removeAttr('required');
        parent.siblings(selectedAidtype).removeClass('hidden').children('.form-control').attr('required', 'required');
    });
    $('.default_aidtype_vocabulary').trigger('change');

    /* generate admin username using organization user identifier while adding new organization by superadmin*/
    $('#organization_user_identifier').keyup(function () {
        $('#admin_username').val($(this).val() + '_admin');
    });

    /* generate group admin username using organization identifier while grouping organizations by superadmin*/
    $('#group_identifier').keyup(function () {
        $('#group_admin_username').val($(this).val() + '_group');
    });

    /* show confirmation box on clicking delete */
    $('.delete, .delete_data').click(function (e) {
        e.preventDefault();
        var location = this.href;

        if ($('#delDialog').length === 0) {
            $('body').append('' +
                '<div class="modal" id="delDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">' +
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

        var delDialog = $('#delDialog');

        var buttons = '' +
            '<button class="btn btn_del" type="button">' + localisedData['yes'] + '</button>' +
            '<button class="btn btn-default" type="button"  data-dismiss="modal">' + localisedData['no'] + '</button>';

        $('.modal-header .modal-title', delDialog).html(localisedData['delete_confirmation']);
        $('.modal-body', delDialog).html(localisedData['delete_sure']);
        $('.modal-footer', delDialog).html(buttons);

        $('body').undelegate('.btn_del', 'click').delegate('.btn_del', 'click', function () {
            window.location = location;
        });

        delDialog.modal('show');
    });

    /* display border on block to be removed while hovering remove button on form */
    $('form').delegate('.remove_from_collection', 'mouseenter mouseleave', function () {
        $(this).parent('.form-group').toggleClass('fill-border');
    });

    /* display border on hovered form block */
    $('form').delegate('.collection_form', 'mouseenter mouseleave', function () {
        $(this).toggleClass('border-line');
    });

    /* display/hide action icon on menu elements */
    $('.element-sidebar-wrapper .panel-body li a').hover(function () {
            $(this).children('.action-icon').css('display', 'block');
        },
        function () {
            $(this).children('.action-icon').css('display', 'none');
        });

    /* change size of menu on hover */
    $('.sidebar-wrapper').hover(function () {
            $(this).addClass('full-sidebar-wrapper');
        },
        function () {
            $(this).removeClass('full-sidebar-wrapper');
        });

    $('.language-selector').click(function () {
        $(this).siblings('.language-flag-wrap').toggle();
    });


    $('.element-menu-wrapper').click(function () {
        $(this).children('.element-sidebar-wrapper').toggle();
        $(this).find('.caret').toggleClass('caret-out');
    });

    $(document).mouseup(function (e) {
        var container = $('.language-flag-wrap');
        if (!container.is(e.target)
            && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    /* add selection to active element page */
    $('.element-sidebar-wrapper a').each(function () {
        var aHref = $(this).attr('href');
        var href = location.href;
        if (href.indexOf(aHref) > -1) {
            $(this).addClass('highlight');
        }
    });

    /* add selection to active page */
    $('.sidebar-wrapper a').each(function () {
        var aHref = $(this).attr('href');
        var href = location.href;
        if (href.indexOf(aHref) > -1) {
            $(this).addClass('active');
        }
    });

    /* prevent multiple submission on multiple click */
    $('form').submit(function () {
        $('[type="submit"]', this).not('.prevent-disable').attr('disabled', 'disabled');
    });

    $(".clickable-row").click(function (e) {
        if (!($(e.target).is('input') || $(e.target).is('a'))) {
            window.document.location = $(this).data("href");
        }
    });

    $(".clickable-row > td > :checkbox").click(function () {
        $(this).parents('.clickable-row').toggleClass('clickable-row-bg');
    });

    /* accordion */
    $('.activity-element-title').click(function () {
        $(this).parent('.panel-heading').toggleClass('activity-element-toggle').next().slideToggle();
    });


    /* auto-generate username while creating user by organization admin */
    $('input[name="userIdentifier"]').on('keyup change', function () {
        var username = '';
        if ($(this).val() == "") {
            $('.username_text').removeClass('hidden');
            $('.username_value').addClass('hidden');
        } else {
            username = $('#userIdentifier').attr('data-org-identifier') + '_' + $(this).val();
            $('.username_text').addClass('hidden');
            $('.username_value').removeClass('hidden');
        }
        $('input[name="username"]').val(username).next('.alternate_input').html(username);
    });
    if ($('input[name="userIdentifier"]').val() !== '') {
        $('input[name="userIdentifier"]').trigger('keyup');
    }

    //disable space for the inputs with class nospace
    $('.noSpace').on('keypress', function (e) {
        if (e.which == 32)
            return false;
    });

    /* snaps element menu on scroll */
    $(window).scroll(function () {
        var elementSidebar = $('.element-sidebar-wrapper');
        if (elementSidebar.length > 0) {
            if ($('.element-menu-wrapper').offset().top - $(this).scrollTop() <= $('.navbar-default').eq(0).height()) {
                elementSidebar.addClass('element-sidebar-fixed');
            } else {
                elementSidebar.removeClass('element-sidebar-fixed');
            }
        }
    });

    //sidebar scrollable
    function menuHeight() {
        var windowHeight = $(window).height();
        var menuSidebar = $('.sidebar-wrapper .nav');
        menuSidebar.css('height', windowHeight - $('.sidebar-wrapper').position().top - menuSidebar.siblings('.support').height() - 20);
    }

    function elementMenuHeight() {
        var windowHeight = $(window).height();
        var elementSidebar = $('.element-sidebar-wrapper');
        var scrollTop = $(document).scrollTop();
        elementSidebar.css('height', windowHeight - (elementSidebar.offset().top - scrollTop));
    }

    var jScrollPaneSettings = {
        showArrows: true,
        arrowScrollOnHover: true,
        autoReinitialise: true,
        autoReinitialiseDelay: 100
    };

    function sidebarHeight() {
        if ($(".element-sidebar-wrapper").length > 0) {
            elementMenuHeight();
        }

        if ($(".sidebar-wrapper .nav").length > 0) {
            menuHeight();
        }
    }

    sidebarHeight();
    $(window).on('resize scroll', sidebarHeight);

    if ($(".element-sidebar-wrapper").length > 0) {
        $(".element-sidebar-wrapper").jScrollPane(jScrollPaneSettings);
    }

    if ($(".sidebar-wrapper .nav").length > 0) {
        $(".sidebar-wrapper .nav").jScrollPane(jScrollPaneSettings);
    }

    var minTextareaHeight = 45;

    function adaptiveheight(a) {
        $(a).height(minTextareaHeight);
        var scrollval = $(a)[0].scrollHeight;
        if (scrollval > minTextareaHeight) {
            $(a).height(scrollval);
        }
        if (parseInt(a.style.height) > $(window).height() - 30) {
            $(document).scrollTop(parseInt(a.style.height));
        }
    }

    /* auto-adjust textarea height */
    $("form").delegate('textarea', 'keyup', function (e) {
        adaptiveheight(this);
    });
    // $("textarea").trigger('keyup');

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scroll-top').fadeIn();
        } else {
            $('.scroll-top').fadeOut();
        }
    });

    $('.scroll-top').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    //if (!Modernizr.svg) {
    //    $('img[src*="svg"]').attr('src', function () {
    //        return $(this).attr('src').replace('.svg', '.png');
    //    });
    //}

    /* initialize calendar to form fields with class datepicker */
    function addDatepicker() {
        if (typeof $.datetimepicker != 'undefined') {
            $('form .datepicker').datetimepicker({
                timepicker: false,
                format: 'Y-m-d',
                formatDate: 'Y-m-d',
                scrollMonth: false
            });
        }
    }

    addDatepicker();

    $('.element-sidebar-wrapper').parents('body').find('.scroll-top').addClass('full-scroll-top');

    function isTouchDevice() {
        return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
    }

    if (isTouchDevice() === true) {
        $('.sidebar-wrapper').addClass('touch-sidebar-wrapper');
    } else {
        //alert('Not a Touch Device'); //your logic for non touch device here
    }

    //js for form input check and leave page alert
    // $('form').delegate('textarea:not(".ignore_change"), select:not(".ignore_change"), input:not(".ignore_change")', 'change keyup', function (e) {
    //     var element = $(e.target);
    //     if (e.isTrigger !== undefined && (element.is('input') || element.is('textarea') || element.is('select'))) {
    //         return false;
    //     }
    //     preventNavigation = true;
    // });

    $('[type="submit"]').click(function () {
        preventNavigation = false;
    });

    window.onbeforeunload = function () {
        var route = getRouteFromUrl();
        if (route == 'publishing-settings') {
            preventNavigation = false;
        }
        if (route != 'register' && ($('.introjs-overlay').length == 0)
        ) {
            if (preventNavigation) {
                return localisedData['unsaved_changes'];
            }
        }
    };

    // Returns the word after '/' of url
    function getRouteFromUrl() {
        var fullUrl = window.location.href;
        var positionOfSlash = fullUrl.lastIndexOf('/');
        return fullUrl.substr(positionOfSlash + 1);
    }

    //activity view
    $(document).on('click', '.show-more-info,.hide-more-info', function () {
        $(this).toggleClass('hidden').siblings('span').toggleClass('hidden');
        $(this).parents('.toggle-btn').next('.more-info').toggleClass('hidden');
    });

    $('.print').click(function () {
        window.print();
    });

    if ($(window).width() > 768) {
        $('.steps-wrapper').addClass('is-open');
    }
    else {
        $('.steps-wrapper').removeClass('is-open');
    }

    $(window).resize(function () {
        if ($(window).width() > 768) {
            $('.steps-wrapper').addClass('is-open');
        }
        else {
            $('.steps-wrapper').removeClass('is-open');
        }
    });

    $('.show-less').click(function () {
        $('.steps-wrapper').removeClass('has-opened').removeClass('is-open');
    });

    $('.show-more').click(function () {
        $('.steps-wrapper').addClass('has-opened');
    });

    //$(".error-listing").jScrollPane();

    $(".show-error-link").click(function () {
        $(this).parent('p').next().slideToggle(200, function () {
            if ($(this).is(':visible')) {
                $('.error-listing').jScrollPane(
                    {reinitialise: true}
                );
            }
        });
        $(this).toggleClass('hide-error-link');
        if ($(this).text() == "Show error(s)")
            $(this).text(localisedData['hide_errors']);
        else
            $(this).text(localisedData['show_errors']);
    });

    $(window).load(function () {
        $('.xml-info ul').jScrollPane({reinitialise: true});
    });


});
