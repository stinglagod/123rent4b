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

// ==============================================================================/
// });

//