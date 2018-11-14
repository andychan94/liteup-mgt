$(document).ready(function () {

    $("#owl-home").owlCarousel({
        navigation: false, // Show next and prev buttons
        dots: false,
        singleItem: true,
        items: 1,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        loop: true,
        animateOut: 'fadeOut',
        animateIn: 'flipInX',
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true
    });

    $('.check-type').click(function () {

        $('.check-type').each(function () {
            $(this).find('input').val('0');
            $(this).removeClass('m-l-active');
            $(this).find('input').prop('checked', false);
        });

        $(this).find('input').prop('checked', true);
        $(this).find('input').val('1');
        $(this).addClass('m-l-active');
    });

    let owl = $("#owl-demo");
    owl.owlCarousel({
        navigation: false, // Show next and prev buttons
        dots: false,
        singleItem: true,
        items: 1,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        loop: true,
        animateOut: 'fadeOut',
        animateIn: 'flipInX',
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        autoHeight: true,
    });
    //
    $('#arend_type').change(function () {
        let $name = $('#arend_type option:selected').attr('data-name');
        $(this).attr('name', $name);
    });

    $('.js-search-select2').select2();

    $('#property_search').keydown(function () {
        let $val = this.value;
        $.ajax({
            url: "/get_property_title_ajax",
            type: "post",
            data: {$val : $val},
            success: function (r) {
                $('.search-result').empty();
                for (let i = 0; i < r.length; i++) {
                    let list = `<li class="list-item" onclick="setVal(this)">${r[i].title.substring(0, 30)}</li>`
                    $('.search-result').append(list)
                }
            }
        });
    });

    $("#listing-property").owlCarousel({
        items: 5,
        margin: 35,
        autoplay: true,
        dots: false,
        responsive: {
            0: {
                items: 1
            },

            576: {
                items: 2
            },

            992: {
                items: 3
            },

            1200: {
                items: 4
            },

            1500: {
                items: 5
            }

        }
    });

});

function setVal(This) {
    let val = $(This).text();
    $(This).parent('.search-result').slideUp(10);
    $('#property_search').val(val)
}

$('#state-select').change(function () {
    let state_id = $(this).val();

    $.ajax({
        url: "/get-lga",
        type: "post",
        data: {state_id: state_id},
        success: function (r) {
            let $lga = $('#lga-select').empty();
            if (r.length > 0) {
                $lga.prop('disabled', false);

                r.forEach(function (item) {
                    let $option = `<option value="${item.id}">${item.name}</option>`;
                    $lga.append($option);
                })
            } else {
                $lga.prop('disabled', true)
            }
        }
    })
});


$('#lga-select').change(function () {
    let lga_id = $(this).val();
    $.ajax({
        url: "/get-area",
        type: "post",
        data: {lga_id: lga_id},
        success: function (r) {
            let $area = $('#area-select').empty();
            if (r.length > 0) {
                $area.prop('disabled', false);
                r.forEach(function (item) {
                    let $option = `<option value="${item.id}">${item.name}</option>`;
                    $area.append($option);
                })
            } else {
                $lga.prop('disabled', true)
            }
        }
    })
})


let houseProce = document.querySelectorAll('.new-house');

houseProce.forEach(function (item) {
    const numberWithCommas = (x) => {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };
    item.innerText = numberWithCommas(item.innerText);
});
