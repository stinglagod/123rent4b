<?php

use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\Site\SiteForm */
/* @var $form ActiveForm */

$this->title = 'Изменение сайта: ' . $site->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['client/client/index']];
$this->params['breadcrumbs'][] = ['label' => $client->name, 'url' => ['client/client/view', 'id' => $client->id]];
$this->params['breadcrumbs'][] = $site->name;
?>
<?php $form = ActiveForm::begin(); ?>
<?php
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Настройки',
        'content'=>$this->render('_tabSettings', [
            'model'=>$model,
            'form'=>$form,
        ]),
        'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Главная страница',
        'content'=>$this->render('_tabMainPage', [
            'model'=>$model,
            'form'=>$form,
            'site'=>$site
        ]),
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Контакты',
        'content'=>$this->render('_tabContact', [
            'model'=>$model,
            'form'=>$form,
        ]),
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Соц. сети',
        'content'=>$this->render('_tabSocNetwork', [
            'model'=>$model,
            'form'=>$form,
        ]),
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> SEO',
        'content'=>$this->render('_tabSEO', [
            'model'=>$model,
            'form'=>$form,
        ]),
    ]
];
?>
<div class="site-update box box-primary">

    <div class="box-header">
        <p>
            <?= Html::a('Вернуться назад',['client/client/view', 'id' => $client->id], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'timezone')->textInput() ?>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <img src='<?=$site->logo_id?$site->logo->getThumbFileUrl('file', 'logo_153x36'):''?>' class='center-block'/>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model->logo, 'files[]')->label(false)->widget(FileInput::class, [
                            'options' => [
                                'accept' => 'image/*',
                                'multiple' => false,
                            ],
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?=TabsX::widget([
                    'items'=>$items,
                    'position'=>TabsX::POS_ABOVE,
                    'encodeLabels'=>false,
                    'enableStickyTabs'=>true,
                    'id'=>'site'
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <p>
            <?= Html::a('Вернуться назад',['client/client/view', 'id' => $client->id], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </p>
    </div>

</div>
<?php ActiveForm::end(); ?>