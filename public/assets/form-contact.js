// Soumission du formulaire pour contact
const $formContact = $('#form_contact');
$formContact.submit(function (e){
    e.preventDefault();
    let data = $formContact.serialize();
    $.ajax({
        url: $formContact.attr('action'),
        type: $formContact.attr('method'),
        data: data,
        complete: function (html){
            $('#form_contact').replaceWith(
                $(html.responseText).find('#form_contact')
            );
            $('#contact_Envoyer').addClass('disabled');

        }
    });
});