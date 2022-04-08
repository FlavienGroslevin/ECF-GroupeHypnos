
// Ajax pour filtrer les suites lié à un hotel
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
// fin du Filtre

// Soumission du formulaire pour afficher les suites dispo.

const $form = $('#form_reservation');
$form.submit(function (e){
    e.preventDefault();
    let data = $form.serialize();
    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: data,
        complete: function (html){
            $('#result').replaceWith(
                $(html.responseText).find('#result'));
        }
    });
});
