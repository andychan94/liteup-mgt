
$('.delete').on('click', function(){
    var el = $(this).closest('.notification');
    cuteHide(el);
});

function cuteHide(el) {
    el.animate({opacity: '0'}, 150, function(){
        el.animate({height: '0px'}, 150, function(){
            el.remove();
        });
    });
}
$(".checkbox").change(function () {
    if (this.checked) {
        $(this).closest("tr").addClass('is-selected');
    }
    else {
        $(this).closest("tr").removeClass('is-selected');
    }
});
$('.check_all').change(function () {
    if (this.checked) {
        $("form input[type='checkbox']").prop('checked', true);
        $("form input.checkbox[type='checkbox']").closest("tr").addClass('is-selected');
    }
    else {
        $("form input[type='checkbox']").prop('checked', false);
        $("form input.checkbox[type='checkbox']").closest("tr").removeClass('is-selected');
    }
});
$("table tbody tr").click(function (event) {
    if (event.target.type !== 'checkbox') {
        $(':checkbox', this).trigger('click');
    }
});
var getExtension = function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
};
var isCsv = function isCsv(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
        case 'csv':
        case 'xls':
        case 'xlsx':
        case 'xlsm':
            return true;
    }
    return false;
};
$(function () {
    $("input:file").change(function () {
        var fileName = $(this).val();
        $("#fileName").html(fileName);

        if (fileName !== "" && isCsv(fileName)) {
            $('#csvUploadBtn').removeAttr("disabled");
        }
        else {
            $('#csvUploadBtn').prop("disabled");
        }
    });
});


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
$(function () {
    var lim = getUrlParameter('limit');
    if (typeof lim !== 'undefined') {
        $("#limit").val(lim);
    }
    else {
        $("#limit").val(limit);
    }
});
$('#limit').change(function () {
    var url = pathWithLimit;
    var item = $('#limit').find(":selected").text();

    window.location.href = url.replace(limit, item);
});

function confirmDelete(csv, nocheck) {
    csv = csv || false;
    nocheck = nocheck || false;
    if (csv) {
        if ($('#deleteAll').is(":checked") || nocheck) {
            return confirm(confirmDelAll);
        }
        return true;
    }
    return confirm(confirmDel);
}