<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.12.2018
 * Time: 10:58
 */
/* @var $this yii\web\View */
/* @var $model \common\models\Movement*/
/* @var $product_id integer*/
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
Modal::begin([
    'header' => '<h4 id="modalTitle"><h4>Приход/уход товара</h4>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static'],
]);
//echo Url::toRoute(['movement/add-ajax','product_id'=>$product_id,'action_id'=>\common\models\Action::PRIHOD]); exit;
?>
<div id='mainModalContent'>
    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Добавить приход'), ['#'], [
            'class' => 'btn btn-success btn-flat addMotion',
            'data-url'=>Url::toRoute(['movement/add-ajax','product_id'=>$product_id,'action_id'=>\common\models\Action::PRIHOD])
        ]) ?>
        <?= Html::a(Yii::t('app', 'Добавить уход'), ['#'], [
            'class' => 'btn btn-warning btn-flat addMotion',
            'data-url'=>Url::toRoute(['movement/add-ajax','product_id'=>$product_id,'action_id'=>\common\models\Action::UHOD])
        ]) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php Pjax::begin(['enablePushState' => false,'id' => 'pjax_movement_grid']); ?>
        <?=$grid?>
        <?php Pjax::end(); ?>
    </div>


</div>
<?php
Modal::end();
$js = <<<JS
    $("body").on("click", '.addMotion', function() {
//        console.log("tut");
//        alert("hi");
        $.get({
                url: this.dataset.url,
                success: function(response){
                    $.pjax({
                        url        : '/admin/movement/index-pjax',
                        container  : '#pjax_movement_grid',
                        push       : false,
                        scrollTo : false
                    });
                },
                error: function(){
                    alert('Error!');
                }
        });
        return false;
    })
JS;

$this->registerJs($js);
?>

