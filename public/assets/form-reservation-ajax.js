const $hotel = $("#reservation_hotels");
$hotel.change(function (){
    let $form = $(this).closest("form");
    let data = {};
    data[$hotel.attr('name')] = $hotel.val();
    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: data,
        complete: function (html) {
            $('#reservation_hotelRooms').replaceWith(
                $(html.responseText).find('#reservation_hotelRooms')
            );
        }
    });
});