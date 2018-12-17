<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 13.12.2018
 * Time: 10:50
 */

?>
<div class="modal fade" id="createNewOrderModal" tabindex="-1" role="dialog" aria-labelledby="createNewOrderModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Создание нового заказа</h4>
            </div>
            <div class="modal-body">

                <?php
                $newOrder = New \common\models\Order();
                $form = ActiveForm::begin(['id'=>'createNewOrderForm']);
                ?>
                <?= $form->field($newOrder, 'name')->textInput(['maxlength' => true]) ?>
                <div class="col-md-6">
                    <?=
                        $form->field($newOrder, 'dateBegin')->widget(DateControl::class, [
                            'type'=>DateControl::FORMAT_DATE,
                            'ajaxConversion'=>false,
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'autoclose' => true
                                ]
                            ]
                        ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($newOrder, 'dateEnd')->widget(DateControl::class, [
                        'type'=>DateControl::FORMAT_DATE,
                        'ajaxConversion'=>false,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ])
                    ?>
                </div>
                <?= $form->field($newOrder, 'customer')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newOrder, 'address')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newOrder, 'description')->textInput(['maxlength' => true]) ?>
                <?php ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <?= Html::submitButton(Yii::t('app', 'Создать'), ['class' => 'btn btn-success btn-flat']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$urlOrder_create_ajax=Url::toRoute("order/create-ajax");
$js = <<<JS
    $('#createNewOrderForm').on('beforeSubmit', function(){
        var data = $(this).serialize();
        var form =$(this);
        $.ajax({
                url: "$urlOrder_create_ajax",
                type: 'POST',
                data: data,
                success: function(response){
                    // console.log(response.data);
                    $.pjax.reload({container: "#pjax_alerts", async: false});
                    form.trigger('reset');
                    $('#createNewOrderModal').modal('hide');
                    $('#orderHeaderBlock').html(response.data);
                },
                error: function(){
                    alert('Error!');
                }
        });
        return false;
    });
JS;
$this->registerJs($js);
?>

