if (typeof(Chunk) == "undefined") var Chunk = {};

(function ($) {

    Chunk = {
        clickPagination: function (route) {
            $('.pagination a').click(function (e) {
                e.preventDefault();
                var pageNo = this.href.substr(this.href.indexOf('=') + 1);
                $('#form-filter').attr("action", route + pageNo);
                $('#form-filter').submit();
            });
        },
        submitFilter: function () {
            $('#form-filter').submit(function () {
                preventNavigation = false;
            });
        },
        toggleData: function (data) {
            $("#json-view").JSONView(data, {
                collapsed: true
            });

            $('#toggle-btn').on('click', function () {
                $('#json-view').JSONView('toggle');
            });
        },
        checkImport: function () {
            var importCheckboxes = $('#import-activities input[type="checkbox"]:not([disabled="disabled"])');
            var submitBtn = $('#import-activities .btn_confirm');
            importCheckboxes.click(function () {
                submitBtn.attr('disabled', 'disabled');
                importCheckboxes.each(function () {
                    if ($(this).prop('checked')) {
                        submitBtn.removeAttr('disabled');
                        return true;
                    }
                });
            });
        }
    }

})(jQuery);