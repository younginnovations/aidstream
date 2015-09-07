$(document).ready(function(){

    var lang = $.cookie('lang');
    $('#def_lang').val(lang == undefined ? 'en' : lang);
    $('#def_lang').change(function(){
        $.cookie('lang', $(this).val(), {path: '/'});
        window.location.reload();
    });

    $('input[name="organization_user_identifier"]').blur(function(){
        $('input[name="username"]').val($(this).val() + '_admin');
    });

    $('.checkAll').click(function() {
        $('.field1').prop('checked', this.checked);
    });

    $('.add-to-collection').on('click', function(e) {
        var container = $('.collection-container');
        count = container.children().length;
        var proto = container.data('prototype').replace(/__NAME__/g, count);
        container.append(proto);
    });
});