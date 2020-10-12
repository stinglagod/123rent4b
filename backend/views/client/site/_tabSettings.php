<?php
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\Site\SiteForm */
?>
Настройки

<!--<pre>-->
<!--    --><?php
//        print_r($model->footer->getErrorSummary(true));
//    ?>
<!--</pre>-->

<?php //нужен какой-то элемент формы, что бы все загрузилось?>
<?= $form->field($model->footer, 'tmp')->label(false)->hiddenInput(['maxlength' => true]) ?>
<div class="box box-primary">
    <div class="box-header">
        Футтер
    </div>
    <div class="box-body">
        <?php /** @var \rent\forms\manage\Client\Site\MainPage\MainPageCategoryForm $item */
        foreach ($model->footer->categories as $key => $item) :?>
            <?= $form->field($item, '['.$key.']category_id')->dropDownList($item->categoriesList(), ['prompt' => '']) ?>
        <?php endforeach;?>
    </div>
</div>