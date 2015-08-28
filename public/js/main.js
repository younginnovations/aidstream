$(document).ready(function(){

    $('#def_lang').val($.cookie('lang'));
    $('#def_lang').change(function(){
        $.cookie('lang', $(this).val(), {path: '/'});
        window.location.reload();
    });

});