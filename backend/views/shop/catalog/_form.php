<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\CategoryForm */
/* @var $form yii\widgets\ActiveForm */
$cancelButton=null;
if ($model->_category) {
    $categoryUrl=['category', 'id' =>$model->_category->id];
    $cancelButton=Html::a('Отмена', $categoryUrl, ['class' => 'btn btn-default']);
}

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= $cancelButton ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Общее</div>
        <div class="box-body">
            <?= $form->field($model, 'parentId')->dropDownList($model->parentCategoriesList()) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
<!--            --><?php //= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'onSite')->checkbox() ?>
            <?php
            //Если раздел не выводится на сайт, тогда и отображение и сайты можно скрыть
            $cssSiteClass='catalog-form-site ';
            if (!$model->onSite) {
                $cssSiteClass.='catalog-form-site hidden';
            }
                ?>
            <?= $form->field($model, 'showWithoutGoods',['options' =>['class'=>$cssSiteClass]])->checkbox() ?>
            <?= $form->field($model->sites, 'others',['options' =>['class'=>$cssSiteClass]])->widget(Select2::class, [
                'data' => $model->sites->sitesList(),
                'options' => ['placeholder' => '', 'multiple' => true,'class'=>$cssSiteClass],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>


        </div>
    </div>

    <div class="box box-default  <?=$cssSiteClass?>">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= $form->field($model->meta, 'title')->textInput() ?>
            <?= $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
            <?= $form->field($model->meta, 'keywords')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= $cancelButton ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js=<<<JS
//транслитерация русского в латиское название 
$("body").on("keyup", '#categoryform-name', function() {
    $('#categoryform-slug').val(cyrillicToLatin(this.value));
})
//Открываем или скрываем настройки для отображения на сайте
$("body").on("change", '#categoryform-onsite', function() {
    console.log(this.checked);
    if (this.checked) {
        $('.catalog-form-site').removeClass('hidden');
    } else {
        $('.catalog-form-site').addClass('hidden');
    }
})
JS;
$this->registerJs($js);
?>