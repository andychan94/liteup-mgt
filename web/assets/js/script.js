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

    $('#property_search').keyup(function () {
        let $val = this.value;
        $.ajax({
            url: "/get-property-title-ajax",
            type: "POST",
            dataType: 'json',
            data: {val: $val},
            success: function (r) {
                let x = JSON.parse(r);
                $('.search-result').empty();
                for (let i = 0; i < x.length; i++) {
                    let list = `<li class="list-item" onclick="setVal(this)">${x[i].title.substring(0, 30)}</li>`
                    $('.search-result').append(list)
                }
            }
        });
    })
});

function setVal(This) {
    let val = $(This).text();
    $(This).parent('.search-result').slideUp(10)
    $('#property_search').val(val)
}