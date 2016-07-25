var Contact = Contact || {};

(function ($) {

    Contact = {
        load: function () {
            this.adaptiveHeight();
            this.validation();
        },
        adaptiveHeight: function () {
            var minTextareaHeight = 45;

            function adaptiveheight(a) {
                $(a).height(minTextareaHeight);
                var scrollval = $(a)[0].scrollHeight;
                if (scrollval > minTextareaHeight) {
                    $(a).height(scrollval);
                }
                if (parseInt(a.style.height) > $(window).height() - 30) {
                    $(document).scrollTop(parseInt(a.style.height));
                }
            }

            /* auto-adjust textarea height */
            $("form").delegate('textarea', 'keyup', function (e) {
                adaptiveheight(this);
            });
            $("textarea").trigger('keyup');
        },
        validation: function () {

            var form = $('#form-contact');

            $.validator.addMethod("email", function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+\@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])\.[.a-zA-Z0-9](?:[.a-zA-Z0-9-]{0,61}[a-zA-Z0-9])*$/.test($.trim(value));
            });

            var validation = form.validate({
                errorClass: 'text-danger',
                highlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').eq(0).addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').eq(0).removeClass('has-error');
                },
                invalidHandler: function () {
                    $('button[type="submit"]', form).removeAttr('disabled');
                }
            });

            $('#full_name', form).rules('add', {required: true, messages: {required: 'Full Name is required.'}});
            $('#email', form).rules('add', {required: true, email: true, messages: {required: 'Email is required.'}});
            $('#message', form).rules('add', {required: true, messages: {required: 'Message is required.'}});
        }
    }

})(jQuery);
