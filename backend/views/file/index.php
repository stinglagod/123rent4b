<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Files1';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-index">
    <?php Pjax::begin(['id' => 'grid-files']) ?>
    <?php $form = ActiveForm::begin(['options' => ['class' => 'pjax-form'], 'action'=>\yii\helpers\Url::to(['/file/operation']),'id'=>'operationFiles']); ?>
    <br>
    <?php if (\Yii::$app->user->can('manager')) { ?>
    <div class="form-group text-right">
            <?= Html::dropDownList('operation','',[
                    1=>'Удалить',
                    2=>'Открыть для клиентов',
                    3=>'Закрыть для клиентов',
                    4=>'Опубликовать на главной',
                    5=>'Убрать с главной'
            ],
                ['class'=>""])?>
            <?= Html::submitButton('Выполнить', ['class' => 'btn btn-success', ]) ?>
    </div>
    <?php } ?>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => 'Изображения',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model, $key, $index, $column) {
                    return Html::img($model['thumb'],['style' => 'padding:2px;width:96px;']);
                }
            ],
            [
                'label' => 'Имя файла',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model, $key, $index, $column) {
                    $html = Html::a($model['name'],$model['url']);
                    return $html;
                }
            ],

            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model) {
                    return ['value' => $model['id']];
                },
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{delete}',
//                'contentOptions' => ['class' => 'action-column'],
//                'buttons' => [
//                    'delete' => function ($url, $model, $key) {
//                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
//                            'title' => 'Удалить',
//                            'data-pjax' => '#grid-files',
//                            'data-method'=>'post'
//                        ]);
//                    },
//                ],
//            ],
        ],
    ]);?>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end() ?>
</div>

<?php
//$js = <<<JS
//        $(document).ready(function() {
//            $("#operationFiles").on('submit', function(e){
//                alert('hi');exit;
//                var form = $(this);
//
//                $.ajax({
//                    type : "POST",
//                    url: form.attr('action'),
//                    data: new FormData(this),
//                    processData: false,
//                    contentType: false,
//                    success  : function(response) {
//                        $.pjax.reload({container:'#products-table', timeout: false});
//                    },
//                    error : function(response){
//                        console.log(response);
//                    }
//                });
//
//                return false;
//
//            }).on('submit', function(e){
//                e.preventDefault();
//            });
//        });
//JS;
//$this->registerJs($js);

?>