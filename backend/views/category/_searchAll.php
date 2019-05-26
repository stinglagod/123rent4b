<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>


    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
        'type'=>ActiveForm::TYPE_HORIZONTAL
    ]); ?>

    <div class="col-md-12">
        <div class="box box-primary сollapsed-box">
            <div class="box-header with-border">
                <div class="form-group row mb-0">
                    <div class="col-sm-7">
                    <?=
                        $form->field($model, 'name')->textInput([
                            'class'=>'form-control pull-left',
                            'placeholder'=>'Поиск'
                        ])->label(false);
                    ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'withoutfolder')->checkbox()->label('Все разделы'); ?>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <?= Html::submitButton('<i class="fa fa-search"></i> Поиск', ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-secondary','id'=>'reset-search','type'=>'reset']) ?>
                        </div>
                    </div>

                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" title="Раскрыть фильтр" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

<!--window.history.pushState(null,null,response.data);-->
<!--$.pjax.reload({container: "#pjax_alerts", async: false});-->
<?php
$js = <<<JS
     $("#reset-search").click(function () {
         // alert('hi');
         $(this).closest('form').get(0).reset();
         // console.log($(location));
         newUrl=window.location.href.replace(window.location.search,'');
         // console.log(newUrl);
         window.history.pushState(null,null,newUrl);
         $.pjax.reload({container: "#pjax_right-detail", async: false});
     })
JS;

$this->registerJs($js);
?>