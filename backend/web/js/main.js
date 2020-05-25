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
                    $.pjax.reload({container: pjaxContainers[index+1]});
                });
            }
        });

        $.pjax.reload({container: pjaxContainers[0]}) ;
    }
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