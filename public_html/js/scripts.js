$('.delete').on('click', function () {
    var el = $(this).closest('.notification');
    cuteHide(el);
});

function cuteHide(el) {
    el.animate({opacity: '0'}, 150, function () {
        el.animate({height: '0px'}, 150, function () {
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

$(function () {
    var areaSelect = $("#areaSelect") || null;
    var lgaSelect = $("#lgaSelect") || null;
    var stateSelect = $("#stateSelect") || null;
    $("#hiddenLgas").html(lgaSelect.clone().prop('id', 'lgaSelectSeed'));
    $("#hiddenAreas").html(areaSelect.clone().prop('id', 'areaSelectSeed'));
    var selectedLga = $("#lgaSelectSeed").find("option:selected").parent().attr('label');
    var selectedArea = $("#areaSelectSeed").find("option:selected").parent().attr('label');
    stateSelect.find("option").filter(function () {
        return $(this).text() === selectedLga;
    }).prop('selected', true);
    lgaSelect.find("option").filter(function () {
        return $(this).text() === selectedArea;
    }).prop('selected', true);
    var select_class = stateSelect.find("option:selected").text();
    var select_class_lga = lgaSelect.find("option:selected").text();
    lgaSelect.children().not('optgroup[label="' + select_class + '"]').remove();
    areaSelect.children().not('optgroup[label="' + select_class_lga + '"]').remove();

    var select_class_ = $("option:selected", this).text();
    var options = $('#areaSelectSeed').find('optgroup[label="' + select_class_ + '"]');
    areaSelect.children().remove();
    areaSelect.append(options);
    var areaLength = areaSelect.find("option").length;
    if (areaLength === 0) {
        areaSelect.prop('disabled', true);
    } else {
        areaSelect.prop('disabled', false);
    }
    stateSelect.change(function () {
        var select_class = $("option:selected", this).text();
        var options = $('#lgaSelectSeed').find('optgroup[label="' + select_class + '"]');
        lgaSelect.children().remove();
        lgaSelect.append(options);

        // var select_class_lga = $("#lgaSelect").find("option:selected").text();
        // var options_lga = $('#areaSelectSeed').find('optgroup[label="' + select_class_lga + '"]');
        // areaSelect.children().remove();
        // areaSelect.append(options_lga);
        // var areaLength = areaSelect.find("option").length;
        // if (areaLength === 0) {
        //     areaSelect.prop('disabled', true);
        // } else {
        //     areaSelect.prop('disabled', false);
        // }
    });
    lgaSelect.change(function () {
        var select_class = $("option:selected", this).text();
        var options = $('#areaSelectSeed').find('optgroup[label="' + select_class + '"]');
        areaSelect.children().remove();
        areaSelect.append(options);
        var areaLength = areaSelect.find("option").length;
        if (areaLength === 0) {
            areaSelect.prop('disabled', true);
        } else {
            areaSelect.prop('disabled', false);
        }
    });

});
$(document).ready(function () {

    $('form').validate({ // initialize the plugin
        debug: true,
        inputErrorClass: 'is-danger',
        errorClass: 'help is-danger',
        validClass: 'is-success',
        errorElement: 'p',
        highlight: function (element, inputErrorClass, validClass) {
            $(element).addClass("is-danger").removeClass(validClass);
        },
        unhighlight: function (element, inputErrorClass, validClass) {
            $(element).removeClass("is-danger").addClass(validClass);
        },
        rules: {
            "fos_user_registration_form[phone]": {
                required: true,
                number: true
            },
            "fos_user_registration_form[plainPassword][first]": {
                required: true,
                pwcheck: true,
                minlength: 8
            },
            "fos_user_registration_form[plainPassword][second]": {
                required: true,
                equalTo: "#fos_user_registration_form_plainPassword_first"
            }
        },
        messages: {
            "fos_user_registration_form[phone]": {
                number: "Please enter a valid phone number (numbers ONLY)"
            },
            "fos_user_registration_form[plainPassword][first]": {
                required: "Password is required",
                pwcheck: "Password must contain at least: one lowercase, one uppercase letter and one number",
                minlength: "Password must be at least 8 characters long"
            },
            "fos_user_registration_form[plainPassword][second]": {
                required: "Password is required",
                equalTo: "Passwords must match"
            }
        }
    });


    $.validator.addMethod("pwcheck",
        function (value) {
            return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
                && /[a-z]/.test(value) // has a lowercase letter
                && /[A-Z]/.test(value) // has an uppercase letter
                && /\d/.test(value) // has a digit
        });

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