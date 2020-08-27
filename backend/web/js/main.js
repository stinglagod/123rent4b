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
    //перемещение блоков
    $("body").on("click", '.add2order', function() {
        $.ajax({
            url: this.href,
            type: this.dataset.method,
            success: function (response) {
                console.log('ok');
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
});


// ==============================================================================/
