$(document).ready(function () {
    window.addEventListener('click', function (e) {
        if (!$.contains(document.getElementById('map'), e.target)) {
            $('#tooltip').css('display', 'none');
        }
    });

    function hamburgerMenu() {
        $('.navbar-toggle.collapsed').click(function () {
            $('.navbar-collapse').toggleClass('out');
            $(this).toggleClass('collapsed');
        });
    }

    hamburgerMenu();
});
