$(document).ready(function () {
    function listDocuments(data) {
        var documentData = data;
        var documentList = '';
        for (var i in documentData) {
            var url = documentData[i].url;
            documentList += '<tr>';
            documentList += '<td>' + url + '</td>';
            documentList += '<td><a href="' + url + '" class="use_this">Use this</a></td>';
            documentList += '<tr>';
        }
        documentList = documentList != '' ? documentList : '<td colspan="2">No documents found.</td>';
        $('#document_list tbody').html(documentList);
    }

    $('#upload_document').on('show.bs.modal', function (e) {
        $.ajax({
            type: 'GET',
            url: '/document/list',
            success: function (data) {
                listDocuments(data);
            }
        });

        var trigger = $(e.relatedTarget);
        var triggerName = trigger.parent().attr('id');
        var targetName = triggerName.replace('upload_text', 'url');
        $(this).attr('data-target-name', targetName);
        $('#upload_message').removeAttr('class').html('');
        if (trigger.attr('data-modal-type') === 'upload') {
            $('.upload_form', this).removeClass('hidden');
        } else {
            $('.upload_form', this).addClass('hidden');
        }
    });

    $('#upload_file').submit(function (e) {
        e.preventDefault();
        var _this = $(this);
        $('body').append('<div class="loader">.....</div>');

        var formData = new FormData(_this[0]);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('[name="_token"]', _this).val()},
            type: _this.attr('method'),
            url: _this.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#upload_message').removeAttr('class').addClass('alert alert-' + data.status).html(data.message);
                if (data.status == 'danger') {
                    return false;
                }
                listDocuments(data.data);
            },
            complete: function () {
                $('body > .loader').addClass('hidden').remove();
            }
        });
    });

    $('tbody').delegate('.use_this', 'click', function (e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var modal = $('#upload_document');
        var targetName = modal.attr('data-target-name');
        $('input[name="' + targetName + '"]').val(link);
        modal.modal('hide');
    });
});
