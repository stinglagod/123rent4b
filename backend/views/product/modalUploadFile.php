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
?>
<div id='modalUploadFileContent'>
        <?=TabsX::widget([
            'items'=>[
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Загрузка',
                        'content'=>FileInput::widget([
                            'name'=>'files[]',
                            'language' => 'ru',
                            'options' => ['multiple' => true],
                            'pluginOptions' => [
                                'previewFileType' => 'any',
                                'uploadUrl' => \yii\helpers\Url::to(['file/upload']),
                                'uploadExtraData' => new JsExpression("function (previewId, index) {
                                    return {
                                        hash: $(\"#modalUploadFileContent\").data(\"hash\"),
                                        contract_id: $(\"#modalUploadFileContent\").data(\"contract_id\")
                                    };
                                    }"),
                            ],
                            'pluginEvents' => [
                                "fileuploaded" => "function() { 
                                                $.pjax.reload({
                                                    url        : \"".$urlModalPjax."\"+$(\"#modalUploadFileContent\").data(\"hash\"),
                                                    replace: false,
                                                    container:\"#grid-files\"
                                                });  //Reload GridView 
                                }",
                            ],
                        ])
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> Файлы',
                        'content'=>'Список файлов',
                        'linkOptions'=>[
//                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
                                'data-url'=>Url::to(['/file/index'])
                        ],

                    ]
            ],
            'pluginEvents' => [
                "tabsX.beforeSend" => "function(event, jqXHR, settings) {
                    console.log($('#modalUploadFileContent'));
//                    console.log(this);
//                    console.log(jqXHR);
                    console.log(settings);
//                    settings.url = ".$urlModalPjax."+$('#modalUploadFileContent').data('hash');
                    settings.url = \"".$urlModalPjax."\"+$(\"#modalUploadFileContent\").data(\"hash\");
                }",
            ],
            'position'=>TabsX::POS_ABOVE,
            'encodeLabels'=>false
        ]);
        ?>
    </div>

<?php
Modal::end();
?>