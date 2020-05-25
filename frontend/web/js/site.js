//JS для работы с корзиной и заказами

$('.addToBasket').on('click',function(){

    if (this.dataset.order_id == '') {
        $.get({
            url: '/order/update-ajax',
            success: function(response){
                // console.log(response);
                $("#modalBlock").html(response.data)
                $('#modal').removeClass('fade');
                $('#modal').modal('show');
            },
            error: function(){
                alert('Error!');
            }
        })


        // var modal=$('#modalUpdateOrder').find('div');
        // modal=modal[0];
        // $(modal).removeClass('fade');
        // $(modal).modal('show');
        return;
    }
    $.ajax({
        url: '/order/add-to-basket',
        type: "POST",
        data: {
            product_id: this.dataset.product_id,
            qty: this.dataset.qty,
            type: this.dataset.type
        },
        success: function (data) {
            if (data.status=='success') {
            }
            reloadPjaxs( "#pjax_alerts", "#cart-panel-pjax")
            // $.pjax.reload({container: "#pjax_alerts", async: false});
        }
    });
    return false;
});

//Создать заказ в модальном окне
$("body").on("click", '.createNewOrder', function() {
    console.log('tut');
    $.get({
        url: this.dataset.url,
        success: function(response){
            // console.log(response);
            $("#modalBlock").html(response.data)
            $('#modal').removeClass('fade');
            $('#modal').modal('show');
        },
        error: function(){
            alert('Error!');
        }
    })

});