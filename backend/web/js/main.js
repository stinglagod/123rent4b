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
        console.log(this);
        console.log(this.href);
        console.log(this.dataset.method);
        console.log(this.dataset.qty);
        $.ajax({
            url: this.href,
            type: this.dataset.method,
            success: function (data) {
                console.log('ok');
                // $.pjax.reload({container: "#pjax_orderBlank"});
            }
        });
        return false;
    });
});


// ==============================================================================/
