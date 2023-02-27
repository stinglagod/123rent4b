//запускается после выбора select2
function changeSelectContact(element) {
    if (element.params.data.id==-1) {
        // alert('Добавляем нового клиента')
        let modal = $('#_modalCreateContact');
        // modal.show();
        modal.modal('show');
    }
}
//Добавление контакта
$('#form-contact-create').on('beforeSubmit', function () {
    let yiiform = $(this);
    let modal = $(this).find('.modal');

    $.ajax({
            type: yiiform.attr('method'),
            url: yiiform.attr('action'),
            data: yiiform.serializeArray(),
        }
    )
        .done(function(data) {
            if(data.success) {

                var newOption = new Option(data.data.contactName, data.data.contactId, false, false);
                $('#orderselect_contact_id').append(newOption).trigger('change');

                $('#orderselect_contact_id').val(data.data.contactId);
                console.log(data.data.contactId);

                modal.modal('hide');
                yiiform.trigger('reset');
                return false;
                // document.location.reload();
            } else if (data.validation) {
                // console.log('server validation failed');
                yiiform.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
            } else {
                console.log('incorrect server response');
            }
            // reloadPjaxs("#pjax_alerts",);
        })
        .fail(function () {
            // request failed
        })

    return false; // prevent default form submission
})


