$(document).on("click", '#testBtn', function (e) {
    e.preventDefault();
    var parentForm = e.target.parentNode;
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
        parentForm.submit();
    });

    delDialog.modal('show');
});

$(window).on('load',function () {
    console.log('hi')
    var highlight = document.querySelector('.highlight');
    if (highlight) {
        const x = () => {
            highlight.scrollIntoView({block: "center" });
        }
        x();
        // setTimeout(x, 1000);
    }
});