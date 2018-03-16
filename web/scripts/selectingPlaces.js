$(function () {
    var places = [];

    function placeListener() {
        if(!$(this).hasClass('booked')){
            var placeNumber = $(this).attr('place-id');
            if($(this).hasClass('chousen')){
                var index = places.indexOf(placeNumber);
                places.splice(index, 1);
            }else{
                places.push(placeNumber);
            }

            $(this).toggleClass('chousen');
        }
    }

    $('.placement .place').on('click', placeListener);
    $('.placement .fplace').on('click', placeListener);

    $('form[name=booking]').on('submit', function (e) {
        $('input[name=chousen]').val(places);
    })
});