<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchForm \rent\forms\Shop\Search\SearchForm */
/* @var $form yii\widgets\ActiveForm */
//TODO надо бы переделать на изящнее
//var_dump(Yii::$app->request->url);exit;
//if (preg_match('#^/admin/shop/order/catalog#is', Yii::$app->request->url, $matches)) {
//    $url=['shop/order/catalog'];
//} else {
//    $url=['shop/catalog'];
//}
$url=[\rent\helpers\CatalogHelper::getUrl().'/catalog'];
?>
<?php //$form = ActiveForm::begin(['action' => ['catalog/search','layout'=>'order'], 'method' => 'get']) ?>
<?php $form = ActiveForm::begin(['action' => $url, 'method' => 'get']) ?>
<div class="box box-primary  collapsed-box">

    <div class="box-header with-border">

        <div class="form-group row mb-0">
            <div class="col-md-5">
                <?= $form->field($searchForm, 'text')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchForm, 'category')->dropDownList($searchForm->categoriesList(), ['prompt' => '']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($searchForm, 'on_site')->checkbox() ?>
            </div>
            <div class="col-md-2">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                <?= Html::a('Очистить', $url, ['class' => 'btn btn-default btn-lg btn-block']) ?>
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