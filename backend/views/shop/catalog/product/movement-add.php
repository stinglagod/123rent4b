<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use \kartik\select2\Select2;
use yii\grid\GridView;
use rent\entities\Shop\Product\Movement\Balance;
use rent\helpers\MovementTypeHelper;
use rent\entities\Shop\Product\Movement\Movement;

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $model \rent\forms\manage\Shop\Product\MovementForm */

$this->title = 'Добавить движения для: '.$product->name;
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => [$product->id]];
$this->params['breadcrumbs'][] = ['label' => 'Движения', 'url' => ['product-movement',$product->id]];
$this->params['breadcrumbs'][] = 'Добавить';
?>
<div class="product-movement-add">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Вернуться назад', ['product-movement','id'=>$product->id], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">Добавляем движение</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'type_id')->widget(Select2::class,[
                        'data' => MovementTypeHelper::movementTypeHandList(),
                        'options' => ['placeholder' => 'Выберите тип движения'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'date_begin')->widget(DateControl::class, [
                        'type'=>DateControl::FORMAT_DATE,
                        'displayFormat' => 'php:d-M-Y',
                        'saveFormat' => 'php:U',
                        'pluginOptions' => [
                            'orientation' => 'top',
                            'convertFormat' => true,
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'date_end')->widget(DateControl::class, [
                        'type'=>DateControl::FORMAT_DATE,
                        'displayFormat' => 'php:d-M-Y',
                        'saveFormat' => 'php:U',
                        'pluginOptions' => [
                            'orientation' => 'top',
                            'convertFormat' => true,
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'qty')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Вернуться назад', ['product-movement',$product->id], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

