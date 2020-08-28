<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Action;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $order rent\entities\Shop\Order\Order */
/* @var $model rent\forms\manage\Shop\Order\OrderCreateForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $blocks \common\models\Block[] */

$this->title = "Создать новый заказ";

$this->params['breadcrumbs'][] = ['label' => 'Все заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Вернуться назад', ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="box box-default">
        <div class="box-header with-border">Новый заказ</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?=
                    $form->field($model, 'date_begin')->widget(DateControl::class, [
                        'type'=>DateControl::FORMAT_DATE,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'convertFormat' => true,
                                'autoclose' => true,
                            ]
                        ]
                    ])
                    ?>
                </div>
                <div class="col-md-3">
                    <?=
                    $form->field($model, 'date_end')->widget(DateControl::class, [
                        'type'=>DateControl::FORMAT_DATE,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'convertFormat' => true,
                                'autoclose' => true,
                            ]
                        ]
                    ])
                    ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->customer, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->customer, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->customer, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model->delivery, 'address')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>