<?php
use kartik\tabs\TabsX;
use yii\bootstrap\Modal;
use kartik\file\FileInput;
use yii\web\JsExpression;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.06.2018
 * Time: 10:51
 */
?>
<?php
Modal::begin([
    'header'=>'<h4>Загрузка файлов</h4>',
    'id'=>'modalUploadFile',
    'size'=>'modal-lg',
]);

$urlModalPjax=Url::toRoute("file/index").'?hash=';
$urlProduct=Url::toRoute("product/update-ajax").'?edit=1&id=';
?>
<div id='modalUploadFileContent1'>
        <?=TabsX::widget([
            'items'=>[
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Загрузка',
                        'content'=>FileInput::widget([
                            'name'=>'files[]',
                            'language' => 'ru',
                            'options' => ['multiple' => false],
                            'pluginOptions' => [
                                'previewFileType' => 'any',
                                'uploadUrl' => \yii\helpers\Url::to(['category/upload','id'=>$model->id]),
                            ],
                            'pluginEvents' => [
                                "fileuploaded" => "function() {
                                }"]

                        ])
                    ],

            ],
            'position'=>TabsX::POS_ABOVE,
            'encodeLabels'=>false
        ]);
        ?>
    </div>

<?php
Modal::end();
?>