$(document).ready(function () {
    var localisedData;

    var callAsync = function (url, requestType) {
        return $.ajax({
            url: url,
            type: requestType
        })
    };

    callAsync('/localisedFormText', 'get').success(function (data) {
        localisedData = JSON.parse(data);
    });

    function listDocuments(data) {
        if (data) {
            var documentData = data;
            var documentList = '';
            for (var i = 0; i < documentData.length; i++) {
                var document = documentData[i];
                var url = '';
                if (document.url) {
                    url = document.url;
                } else if (document.filename) {
                    url = location.origin + '/files/documents/' + encodeURI(document.filename);
                }
                documentList += '<tr>';
                documentList += '<td>' + encodeURI(document.filename) + '</td>';
                documentList += '<td><a href="' + url + '" class="use_this">' + localisedData['use_this'] + '</a></td>';
                documentList += '<tr>';
            }
            documentList = documentList !== '' ? documentList : '<td colspan="2">' + localisedData['no_documents_found'] + '</td>';
            $('#document_list tbody').html(documentList);
        }
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

    $('#file').change(function () {
        if (this.files[0].size > 5000000) {
            $('#message').css('color', 'red').html('File size must be less then 5 MB.');
            $('#upload_document button[type="submit"]').addClass('disabled');
        }
        if(this.files[0].size <= 5000000) {
            $('#upload_document button[type="submit"]').removeClass('disabled');
            $('#message').html('');
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
                if (data.status === 'danger') {
                    return false;
                }
                listDocuments(data.data);
            },
            complete: function () {
                $('body > .loader').addClass('hidden').remove();
                $('[type="submit"]', _this).removeAttr('disabled');
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
