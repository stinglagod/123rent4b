<?php
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use rent\forms\manage\Client\ClientChangeForm;
use yii\helpers\ArrayHelper;
use rent\entities\Client\Site;

/* @var $this \yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $clientChangeForm ClientChangeForm */

$clientChangeForm=Yii::$app->view->params['clientChangForm'];
$form = ActiveForm::begin();
echo '<div class="navbar-right">';
echo '<div class="navbar-client">';
echo $form->field($clientChangeForm, 'client_id')
    ->label(false)
    ->dropDownList($clientChangeForm->clientsList(), [
        'id' => 'dep-drop_client',
    ]);
echo '</div><div class="navbar-client">';
echo $form->field($clientChangeForm, 'site_id')->label(false)->widget(DepDrop::class, [
    'options'=>[
        'id'=>'dep-drop_site'
    ],
    'data'=>ArrayHelper::map(Site::find()->where(['client_id'=>$clientChangeForm->client_id])->orderBy('domain')->asArray()->all(), 'id','domain'),
    'pluginOptions'=>[
        'depends'=>['dep-drop_client'],
        'placeholder'=>'Выберите...',
        'url'=>Url::to(['/client/site/list-dep-drop'])
    ]
]);
echo '</div>';
echo '<div class="navbar-client">';
echo Html::a('<span class="glyphicon glyphicon-ok"></span>', ['client/client/change-site'], [
    'class' => 'btn btn-default',
    'title' => 'Сохранить',
    'data-method' => 'post',
]);
echo '</div>';
echo '</div>';
ActiveForm::end();
