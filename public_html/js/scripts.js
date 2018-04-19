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