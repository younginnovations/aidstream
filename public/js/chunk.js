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
        toggleData: function (data) {
            $("#json-view").JSONView(data, {
                collapsed: true
            });

            $('#toggle-btn').on('click', function () {
                $('#json-view').JSONView('toggle');
            });
        }
    }

})(jQuery);