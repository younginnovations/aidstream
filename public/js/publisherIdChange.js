var PublisherId = {
    publisherNotFoundCode: '104',
    asyncCall: function (data, url, type, beforeSend, complete) {
        return $.ajax({
            headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
            url: url,
            data: data,
            type: type,
            beforeSend: function () {
                if (beforeSend != undefined) {
                    beforeSend()
                }
            },
            complete: function () {
                if (complete != undefined) {
                    complete()
                }
            }
        });
    },
    onChange: function () {
        var self = this;
        $('#publisher_id').on('blur', function () {
            var publisherId = $(this).val();
            var apiKey = $('#api_id').val();
            var publisherIdStatus = $("#publisher_id_status_display");
            var apiIdStatus = $("#api_id_status_display");
            if (!$('.loader').length > 0) {
                self.asyncCall({publisherId: publisherId, apiKey: apiKey},
                    '/publishing-settings/publisherIdChanged', 'POST', self.loadLoader, self.closeLoader)
                    .success(function (data) {
                        var status = '';
                        var publisherIdChanged = $('#publisherIdChanged');

                        if (data.status == 'Incorrect') {
                            status = {'publisher_id': 'Incorrect'};
                            self.storeValues(status);
                        }

                        if (data.status == 'Correct') {
                            status = {'publisher_id': 'Correct'};
                            self.storeValues(status);
                        }

                        if (data.status === false) {
                            status = {'publisher_id': 'Incorrect'};
                            self.storeValues(status, publisherIdStatus);
                        }

                        if (data.loadModal != false) {
                            publisherIdChanged.append(data);
                            publisherIdChanged.modal({backdrop: 'static'});
                            status = {'publisher_id': 'Correct'};
                            self.storeValues(status, publisherIdStatus);
                        }
                    });
            }
        });
    },
    loadLoader: function () {
        $('body').append('<div class="loader">.....</div>');
    },
    closeLoader: function () {
        $('body > .loader').addClass('hidden').remove();
    },
    verifyingStatus: function () {
        var apiStatus = $('#apiStatus');
        apiStatus.html('Verifying...');
    },
    storeValues: function (data) {
        var publisher_response = data['publisher_id'];
        var publisherStatus = (publisher_response == 'Correct') ? true : false;
        $("[name = 'publisher_id_status']").val(publisher_response);
        $("#publisher_id_status_display").removeClass('text-danger text-success').addClass(publisherStatus ? 'text-success' : 'text-danger').html(publisher_response);
    },
    verifyApi: function () {
        var self = this;
        $('#publisherIdChanged').on('blur', '#newApiKey', function () {
            var publisherId = $('#publisher_id').val();
            var apiKey = $(this).val();
            var apiStatus = $('#apiStatus');
            if (apiKey != "") {
                self.asyncCall({publisherId: publisherId, apiKey: apiKey},
                    '/publishing-settings/verifyApiWithPublisherId', 'POST', self.verifyingStatus())
                    .success(function (data) {
                        if (data.status == true) {
                            apiStatus.html('Correct');
                            $('#saveChanges').prop('disabled', false);
                        }
                        if (data.status == false) {
                            apiStatus.html('Incorrect');
                        }
                    })
            }
        });
    },
    checkStatus: function () {
        var self = this;
        var successBar = $('.alert-success');
        var errorBar = $('#error');
        var interval = setInterval(function () {
            self.asyncCall('', '/publishing-settings/publisherIdChangeStatus', 'get')
                .success(function (data) {
                    if (data.status == 'Processing') {
                        if (successBar.length <= 1) {
                            successBar.removeClass('hidden').html('Your publisher id is being changed. In the meantime, you cannot change your registry settings. Thank you for your patience.');
                        }
                    }
                    if (data.status == 'Completed') {
                        clearInterval(interval);
                        self.completeProcess();
                        successBar.html('Publisher Id successfully changed');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }

                    if (data.status == false) {
                        successBar.addClass('hidden');
                        errorBar.removeClass('hidden').html(data.message);
                        self.completeProcess();
                        clearInterval(interval);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                });
        }, 2000);
    },
    completeProcess: function () {
        var self = this;
        self.asyncCall('', '/publishing-settings/completePublisherIdChange', 'post').success(function (data) {
            if (data.status == 'failed') {
                $('#errorBar').html('Failed to complete publisher id change process');
            }
        });
    },
    disableFormSubmit: function () {
        $('#verify').on('submit', function (e) {
            e.preventDefault();
        });
    }
};