<?php
use yii\helpers\Html;

use yii\helpers\Url;
use yii\widgets\Pjax;


/* @var $this \yii\web\View */
/* @var $content string */

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">R4B</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=Yii::$app->user->identity->avatarUrl?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?=Yii::$app->user->identity->shortName?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?=Yii::$app->user->identity->avatarUrl?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?=Yii::$app->user->identity->shortName?> - Web Developer
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Профиль</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выйти',
                                    ['/auth/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
        <?=$this->render('_header-clients',[]);?>
    </nav>
</header>
<!-- Modal -->

<?php
//TODO: перенести в js файл
//$urlOrder_create_ajax=Url::toRoute("order/create-ajax");
$urlOrder_update_ajax=Url::toRoute("order/update-ajax");
$urlOrder_index_ajax=Url::toRoute("order/index-ajax");

$js = <<<JS
    
    // $("#orderHeaderBlock").on("click", '.createNewOrder', function() {
    //     $('#createNewOrderModal').modal('show')
    //     return false
    // });
    //При выборе заказа в мини корзине
//    $("#orderHeaderBlock").on("click", '.orderItem', function() {
//        // console.log(this.dataset.id);
//         $.ajax({
//                url: "$urlOrder_index_ajax",
//                type: 'POST',
//                data:  {'activeId' : this.dataset.id},
//                success: function(response){
//                    // console.log(response.data);
//                    $('#orderHeaderBlock').html(response.data);
//                },
//                error: function(){
//                    alert('Error!');
//                }
//        });
//    });

    //Создать заказ в модальном окне   
    $("body").on("click", '.createNewOrder', function() {
        // console.log('tut');
        $.get({
                url: "$urlOrder_update_ajax",
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
    //редактируем заказ в модальном окне
    $("body").on("click", '.settingsOrder', function() {
        $.get({
                url: "$urlOrder_update_ajax",
                data:  {'id' : this.dataset.id},
                success: function(response){
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show');
                },
                error: function(){
                    alert('Error!');
                }
        })

    });

JS;
$this->registerJs($js);
?>
