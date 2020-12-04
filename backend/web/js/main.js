// $(document).ready(function() {
// =======================Общий блок==========================================/
    //функция перезагрузи pjax контейнеров поочередно.
    //аргументы id pjax контейнерова
    function reloadPjaxs() {
        // var now = new Date();
        // console.log('вызов-'+now);

        var pjaxContainers = arguments;
        $.each(pjaxContainers , function(index, container) {
            // console.log(container);
            if (index+1 < pjaxContainers.length) {
                $(container).one('pjax:end', function (xhr, options) {
                    $.pjax.reload(pjaxContainers[index+1],{timeout : false});
                });
            }
        });

        $.pjax.reload(pjaxContainers[0],{timeout : false}) ;
    }
// =======================блок layots============================================/


// ==============================================================================/
// =======================блок /order/*==========================================/
//выводие превью изображения при наведении на названии товара
$(document).ready( function () {
    $('.popover-product-name').popover({
        html: true,
        trigger: 'hover',
        // content: function () {
            // return '<img  style= "object-fit: cover; height:'+$(this).data('imageheight') + 'px; width:'+$(this).data('imagewidth') + 'px" src="'+$(this).data('imagesrc') + '"/>';
            // return 'tets';
        // }
    });
});
// ==============================================================================/
// =======================блок /category/*==========================================/




// ==============================================================================/

// =======================блок shop/order/catalog/==========================================/
$(document).ready( function () {
    //добавление товара в заказ
    $("body").on("click", '.add2order', function() {
        $.ajax({
            url: this.href,
            type: this.dataset.method,
            success: function (response) {
                // console.log('ok');
                // console.log(response);
                if (response.status == 'success') {
                    if (windowOrder=window.opener){
                        let f1=$.pjax.reload('#pjax_alerts',{timeout : false});
                        let f2=windowOrder.reloadPjaxs("#grid_" + response.data.block_id + "-pjax");
                        $.when.apply(f1, f2).done();
                    }
                } else {
                    reloadPjaxs('#pjax_alerts');
                }

            }
        });
        return false;
    });
    $("body").on("click", '.chk_on_site', function() {
        // console.log("click")
        let el=$(this);
        let set;
        if (el.val()==1) {
            set=0;
        } else {
            set=1;
        }
        let url=this.dataset.url+'&on='+set;
        let request=$.ajax({
            url: url,
            type: this.dataset.method,
            success: function (response) {
                // console.log(response);
                if (response.status == 'success') {
                    el.prop('checked', !!+response.data);
                    el.val(response.data);
                } else {
                    reloadPjaxs('#pjax_alerts');
                }
            }
        });
        request.fail(function (response){
            reloadPjaxs('#pjax_alerts');
        })
        return false;
    });

});


// ==============================================================================/
// =======================блок /order/item/grid*==========================================/
// Найден небольшой глюк с Editable. событие editableSuccess возникает после перезагрузки gridа pjax.
// Поэтому при обновлении событие срабатывается и все pjax обновляются.
// Сделал проверку на первый запуск...
// На 20200829 все не нормально
let first=0;
let gridOrderItem = {
    onEditableGridSuccess: function (event, val, form, response) {

        if (first) {
            if (response.data.block_id) {
                reloadPjaxs('#grid_' + response.data.block_id + '-pjax', '#sum-order-pjax', '#sum-order-tab-payment-pjax');
            } else {
                reloadPjaxs('#sum-order-pjax', '#sum-order-tab-payment-pjax');
            }
        }

    },
    onEditableGridSubmit: function (val, form) {
        first=1;
    }
};

// ==============================================================================/