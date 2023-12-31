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

//if (Yii::$app->settings->site) {
//    echo Yii::$app->settings->site->id;
//} else {
//    echo 'Сайт не указан';
//}

//var_dump(Yii::$app->settings->client);

echo '<div class="navbar-right">';

if (Yii::$app->user->can('super_admin')) {
    $clientChangeForm=new ClientChangeForm();
    $form = ActiveForm::begin([
        'id' => 'header-clients_form'
    ]);

    echo '<div class="navbar-client">';
    echo $form->field($clientChangeForm, 'client_id')
        ->label(false)
        ->dropDownList($clientChangeForm->clientsList(), [
            'id' => 'dep-drop_client',
        ]);
    echo '</div>';
    echo '<div class="navbar-client">';
    echo Html::a('<span class="glyphicon glyphicon-ok"></span>', '#', [
        'class' => 'btn btn-default',
        'title' => 'Сохранить',
        'data-method' => 'post',
    ]);
    echo '</div>';
    ActiveForm::end();
}


echo '</div>';


$js = <<<JS
    $("#header-clients_form").on('beforeSubmit', function(){
        var form = $(this);
        var data=form.serialize();
        // return false;
        $.post( '/admin/client/client/change-client', data, function(response){
            if (response.status=="success") {
                if (response.data.defaultSite) {
                    let oldUrl=document.location.href;
                    let oldDomain=document.location.host;
                    let newUrl= oldUrl.replace(oldDomain, response.data.defaultSite);
                    console.log(response.data.defaultSite);   
                    console.log(newUrl);   
                    window.location.href = newUrl;
                } else {
                    document.location.reload();    
                }
            }
        });
        reloadPjaxs("#pjax_alerts");
        return false;
    })
JS;

$this->registerJs($js);
