$(document).ready(function () {
    if ($.fn.DataTable != undefined) {
        var t = $('#data-table').DataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["no-sort"]
            }],
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
            "order": [[$('table .default-sort').index(), 'desc']]
        });
    }
});