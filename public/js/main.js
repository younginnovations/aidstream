$(document).ready(function () {

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
        var protoHtml = level === 0 ? $('.collection-container') : $('.' + collection, '.collection-container');
        protoHtml.children('label').remove();
        var proto = protoHtml.html();
        for (var i = 0; i < level; i++) {
            proto = proto.replace(
                new RegExp('__NAME' + i + '__', 'g'),
                parentIndexes[i]
            );
        }
        proto = proto.replace(new RegExp('__NAME' + level + '__', 'g'), newIndex);
        proto = proto.replace(/__NAME[\d]+__/g, 0);
        container.append(proto);
    });

    /* Removes form on click to Remove This button */
    $('form').delegate('.remove_from_collection', 'click', function () {
        $(this).parent('.form-group').remove();
    });

    var language = $.cookie('language');
    $('#def_lang').val(language === undefined ? 'en' : language);
    $('#def_lang').change(function () {
        $.cookie('language', $(this).val(), {path: '/'});
        window.location.reload();
    });

    $('input[name="organization_user_identifier"]').keyup(function () {
        $('input[name="username"]').val($(this).val() + '_admin');
    });

    $('input[name="activity_identifier"]').keyup(function () {
        $('input[name="iati_identifier_text"]').val($('#reporting_organization_identifier').text() + '-' + $(this).val());
    });

    $('.checkAll').click(function () {
        $('.field1').prop('checked', this.checked);
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

        if ($('#popDialog').length == 0) {
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
        if ($(this).val() !== "1") {
            parent.siblings('.sector_text').removeClass('hidden');
            parent.siblings('.sector_select').addClass('hidden');
        } else {
            parent.siblings('.sector_select').removeClass('hidden');
            parent.siblings('.sector_text').addClass('hidden');
        }
    });
    $('.sector_vocabulary').trigger('change');

});
