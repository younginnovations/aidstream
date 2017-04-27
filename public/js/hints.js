var className = (hintStatus == 1) ? 'pull-right display Yes' : 'pull-right display No';

$('#logout').before(
    "<li class='dashboard-tour'>" +
    "<span>Dashboard tour</span><a href='#' class='" + className + "' id='hints'></a></li>");

var endTour = function () {
    introJs().exit();
    $('.introjs-tooltip').hide();
    $("[data-step='1']").removeClass('open');
    $(document).on('click');
    completedTour = 1;
};

var goNext = function (step) {
    $("a[data-step=" + step + "]").trigger('click');
};

var skip = function (step) {
    $(".introjs-tooltip").hide();
    $('#hints').trigger('click');
    if (completedTour == 0) {
        $("[data-step='1']").addClass('open');
        UserOnBoarding.finalHints();
        $(document).off('click');
        $('.introjs-tooltip').css({'right': '270px', 'top': '87px'});
        $('.introjs-arrow').css({'right': '-18px', 'top': '50px'});

    }
};

$('#hints').on('click', function () {
    if ($(this).hasClass("Yes")) {
        $(this).removeClass('Yes');
        $(this).addClass('No');
        $('.introjs-hints').css('visibility', 'hidden');
        UserOnBoarding.storeHintStatus(0);
    } else if ($(this).hasClass("No")) {
        $(this).removeClass('No');
        $(this).addClass('Yes');
        $('.introjs-hints').css('visibility', 'visible');
        UserOnBoarding.storeHintStatus(1);
    }
});

if (!UserOnBoarding.loadedLocalisedFile) {
    UserOnBoarding.getLocalisedHintText();
}
