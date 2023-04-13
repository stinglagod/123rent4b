$(document).on('click', '.btn-group .btn.disabled', function(event) {
    event.stopPropagation();
    event.preventDefault();
});