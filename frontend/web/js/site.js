//JS для работы с корзиной и заказами
$("body").on("click", '.btn-add-ajax', function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('href'),
        success: function (response) {
            if (response.status=='success') {
                let data = response.data;
                data.forEach((item) => {
                    console.log(item)
                    $('#'+item.id).html(item.html)
                })
            }
        }
    });
});