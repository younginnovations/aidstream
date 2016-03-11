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

    bindTooltip();

    /* Add form on click to Add More button */
    $('form').delegate('.add_to_collection', 'click', function () {
        var collection = $(this).attr('data-collection');
        var container = $(this).prev('.collection_form');
        var parents = $(this).parents('.collection_form');
        var level = parents.length;
        var indexString = $(' > .form-group:last-child .form-control', container).eq(0).attr('name');
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
        container.append(proto);

        bindTooltip();
        $('form select').select2();
    });

    /* Removes form on click to Remove This button */
    $('form').delegate('.remove_from_collection', 'click', function () {
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
            '<button class="btn btn-primary btn_remove" type="button">Yes</button>' +
            '<button class="btn btn-default" type="button"  data-dismiss="modal">No</button>';

        $('.modal-header .modal-title', removeDialog).html('Remove Confirmation');
        $('.modal-body', removeDialog).html('Are you sure you want to remove this block?');
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
            var indexString = $(' > .form-group:last-child .form-control', collectionForm).eq(0).attr('name');

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
                var fieldName = fieldNames[i];
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

    var language = $.cookie('language');
    language = language ? language : 'en';
    $('.language-select-wrap .flag-icon-' + language).parent().addClass('active');
    $('.language-select-wrap .flag-wrapper').click(function () {
        $.cookie('language', $(this).attr('data-lang'), {path: '/'});
        window.location.reload();
    });

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

    $('.hover_help_text').hover(function () {
        $(this).next('.help-text').trigger('mouseover');
    }, function () {
        $(this).next('.help-text').trigger('mouseout');
    });

    $('.checkAll').click(function () {
        var checked = this.checked;
        $('.field1').not('[readonly="readonly"]').prop('checked', checked);
        $('.check-text').text(checked ? 'Uncheck All' : 'Check All');
    });

    $('input[type="checkbox"][readonly="readonly"]').change(function () {
        $(this).prop('checked', !this.checked);
    });

    $('form').delegate('select[readonly=readonly]', 'mousedown', function (e) {
        e.preventDefault();
    });

    /*
     * Confirmation for form submission
     * Usage:
     * Define Submit button params as:
     *   type = "button"
     *   class="btn_confirm"
     *   data-title="confirmation title" (optional)
     *   data-message="confirmation message"
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
        parent.siblings('.sector_types').addClass('hidden');
        parent.siblings(selectedSector).removeClass('hidden');
    });
    $('.sector_vocabulary').trigger('change');

    /* generate admin username using organization user identifier while adding new organization by superadmin*/
    $('#organization_user_identifier').keyup(function () {
        $('#admin_username').val($(this).val() + '_admin');
    });

    /* generate group admin username using organization identifier while grouping organizations by superadmin*/
    $('#group_identifier').keyup(function () {
        $('#group_admin_username').val($(this).val() + '_group');
    });

    $('.delete').click(function (e) {
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
            '<button class="btn btn_del" type="button">Yes</button>' +
            '<button class="btn btn-default" type="button"  data-dismiss="modal">No</button>';

        $('.modal-header .modal-title', delDialog).html('Delete Confirmation');
        $('.modal-body', delDialog).html('Are you sure you want to delete?');
        $('.modal-footer', delDialog).html(buttons);

        $('body').undelegate('.btn_del', 'click').delegate('.btn_del', 'click', function () {
            window.location = location;
        });

        delDialog.modal('show');

    });

    $('form').delegate('.remove_from_collection', 'mouseenter mouseleave', function () {
        $(this).parent('.form-group').toggleClass('fill-border');
    });

    $('form').delegate('.collection_form', 'mouseenter mouseleave', function () {
        $(this).toggleClass('border-line');
    });

    $('.element-sidebar-wrapper .panel-body li a').hover(function () {
            $(this).children('.action-icon').css('display', 'block');
        },
        function () {
            $(this).children('.action-icon').css('display', 'none');
        });

    $('.sidebar-wrapper').hover(function () {
            $(this).addClass('full-sidebar-wrapper');
        },
        function () {
            $(this).removeClass('full-sidebar-wrapper');
        });

    //js for form input check and leave page alert
    var preventNavigation = false;
    $('form').delegate('textarea, select, input:not(".ignore_change")', 'change keyup', function () {
        preventNavigation = true;
    });

    $('.language-selector').click(function () {
        $(this).siblings('.language-flag-wrap').toggle();
    });


    //js for form input check and leave page alert
    var preventNavigation = false;
    $('form').delegate('textarea, select, input:not(".ignore_change")', 'change keyup', function (e) {
        preventNavigation = true;
    });

    $('[type="submit"]').click(function () {
        preventNavigation = false;
    });

    window.onbeforeunload = function () {
        if (preventNavigation) {
            return 'You have unsaved changes.';
        }
    };

    $('.element-menu-wrapper').click(function () {
        $(this).children('.element-sidebar-wrapper').toggle();
    });

    $(document).mouseup(function (e) {
        var container = $('.language-flag-wrap');
        if (!container.is(e.target)
            && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    $('.element-sidebar-wrapper a').each(function () {
        var aHref = $(this).attr('href');
        var href = location.href;
        if (href.indexOf(aHref) > -1) {
            $(this).addClass('highlight');
        }
    });

    $('.sidebar-wrapper a').each(function () {
        var aHref = $(this).attr('href');
        var href = location.href;
        if (href.indexOf(aHref) > -1) {
            $(this).addClass('active');
        }
    });

    $('form').submit(function () {
        $('[type="submit"]', this).attr('disabled', 'disabled');
    });

    window.onbeforeunload = function () {
        if (preventNavigation) {
            return 'You have unsaved changes.';
        }
    };

    $(".clickable-row").click(function (e) {
        if (!($(e.target).is('input') || $(e.target).is('a'))) {
            window.document.location = $(this).data("href");
        }
    });

    $(".clickable-row > td > :checkbox").click(function () {
        $(this).parents('.clickable-row').toggleClass('clickable-row-bg');
    });

    $('.activity-element-title').click(function () {
        $(this).parent('.panel-heading').toggleClass('activity-element-toggle').next().slideToggle();
    });


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

    $(window).scroll(function () {
        var elementSidebar = $('.element-sidebar-wrapper');
        if (elementSidebar.length > 0) {
            if ($(this).scrollTop() > elementSidebar.position().top) {
                elementSidebar.addClass('element-sidebar-fixed');
            } else {
                elementSidebar.removeClass('element-sidebar-fixed');
            }
        }
    });

    //sidebar scrollable

    var documentHeight = $(window).height() - 63;
    $('.element-sidebar-wrapper').css('height', documentHeight);
    $('.sidebar-wrapper .nav').css('height', documentHeight - 120);

    $(window).resize(function () {
        var documentHeight = $(window).height() - 63;
        $('.element-sidebar-wrapper').css('height', documentHeight);
        $('.sidebar-wrapper .nav').css('height', documentHeight - 120);
    });

    if ($(".element-sidebar-wrapper, .sidebar-wrapper .nav").length > 0) {
        $(".element-sidebar-wrapper, .sidebar-wrapper .nav").mCustomScrollbar({
            theme: "minimal"
        });
    }

    $("textarea").keyup(function (e) {
        adaptiveheight(this);
    });
    $("textarea").trigger('keyup');

    var minTextareaHeight = 45;
    function adaptiveheight(a) {
        $(a).height(minTextareaHeight);
        var scrollval = $(a)[0].scrollHeight;
        if(scrollval > minTextareaHeight) {
            $(a).height(scrollval);
        }
        if (parseInt(a.style.height) > $(window).height() - 30) {
            $(document).scrollTop(parseInt(a.style.height));
        }
    }

});
