<?php

use kartik\select2\Select2;
use rent\forms\manage\Client\Site\SiteChangeForm;
use yii\widgets\ActiveForm;

$siteChangeForm=new SiteChangeForm();
$form = ActiveForm::begin([]);

echo $form->field($siteChangeForm, 'site_id')->widget(Select2::class, [
    'data' => $siteChangeForm->sitesList(),
    'options' => ['placeholder' => 'Все сайты ...','class' => 'widget-site_form'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
ActiveForm::end();

$js = <<<JS
    $(".widget-site_form").on('change', function(){
        var form = $(this);
        var data=form.serialize();
        $.post( '/admin/client/client/change-site', data, function(response){
            if (response.status=="success") {
                document.location.reload();
            }
        });
        reloadPjaxs("#pjax_alerts");
        return false;
    })
JS;

$this->registerJs($js);
?>