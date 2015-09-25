$(document).ready(function () {

    var lang = $.cookie('lang');
    $('#def_lang').val(lang == undefined ? 'en' : lang);
    $('#def_lang').change(function () {
        $.cookie('lang', $(this).val(), {path: '/'});
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

    $('.add-to-collection').on('click', function (e) {
        e.preventDefault();
        var container = $('.collection-container');
        var count = container.children('.form-group').length;
        var proto = container.data('prototype').replace(/__NAME__/g, count).replace(/__NAME1__/g, 0).replace(/__NAME2__/g, 0);
        container.append(proto);
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
            $('body').append('\
                <div class="modal" id="popDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">\
                    <div class="modal-dialog">\
                        <div class="modal-content">\
                            <div class="modal-header">\
                                <h4 class="modal-title" id="myModalLabel"></h4>\
                            </div>\
                            <div class="modal-body">\
                            </div>\
                            <div class="modal-footer">\
                            </div>\
                        </div>\
                    </div>\
                </div>');
        }

        var popElem = $('#popDialog');

        if (title == undefined)
            $('.modal-header', popElem).addClass('hidden').children('.modal-title').html('');
        else
            $('.modal-header', popElem).removeClass('hidden').children('.modal-title').html(title);

        $('.modal-body', popElem).html(message);

        var buttons = '\
            <button class="btn btn-primary btn_yes" type="button">Yes</button>\
            <button class="btn btn-default" type="button"  data-dismiss="modal">No</button>\
        ';
        $('.modal-footer', popElem).html(buttons);

        $('body').undelegate('.btn_yes', 'click').delegate('.btn_yes', 'click', function () {
            $('#' + formId).submit();
        });

        popElem.modal('show');

    });

});