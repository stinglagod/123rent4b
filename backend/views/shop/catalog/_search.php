<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchForm \rent\forms\Shop\Search\SearchForm */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['action' => '', 'method' => 'get']) ?>
<div class="box box-primary  collapsed-box">

    <div class="box-header with-border">

        <div class="form-group row mb-0">
            <div class="col-md-6">
                <?= $form->field($searchForm, 'text')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchForm, 'category')->dropDownList($searchForm->categoriesList(), ['prompt' => '']) ?>
            </div>
            <div class="col-md-2">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                <?= Html::a('Clear', ['shop/catalog'], ['class' => 'btn btn-default btn-lg btn-block']) ?>
            </div>
        </div>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" title="Раскрыть фильтр" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="box-body" >
        <?php foreach ($searchForm->values as $i => $value): ?>
            <div class="row">
                <div class="col-md-4">
                    <?= Html::encode($value->getCharacteristicName()) ?>:
                </div>
                <?php if ($variants = $value->variantsList()): ?>
                    <div class="col-md-4">
                        <?= $form->field($value, '[' . $i . ']equal')->dropDownList($variants, ['prompt' => '']) ?>
                    </div>
                <?php elseif ($value->isAttributeSafe('from') && $value->isAttributeSafe('to')): ?>
                    <div class="col-md-2">
                        <?= $form->field($value, '[' . $i . ']from')->textInput() ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($value, '[' . $i . ']to')->textInput() ?>
                    </div>
                <?php else: ?>
                    <div class="col-md-4">
                        <?= $form->field($value, '[' . $i . ']equal')->label(false)->textInput() ?>
                    </div>
                <?php endif ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
<?php ActiveForm::end() ?>

<?php
$js = <<<JS

JS;

$this->registerJs($js);
?>