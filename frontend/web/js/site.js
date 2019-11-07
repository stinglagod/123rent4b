//JS для работы с корзиной и заказами

$('.addToBasket').on('click',function(){

    console.log(this.dataset.qty);
    console.log(this.dataset.product_id);
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
            $.pjax.reload({container: "#pjax_alerts", async: false});
        }
    });
    return false;
});