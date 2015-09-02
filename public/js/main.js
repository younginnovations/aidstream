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

});