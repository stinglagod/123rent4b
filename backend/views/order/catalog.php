<?php
use yii\widgets\Pjax;
use \yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 26.02.2019
 * Time: 15:16
 */

?>
<?php Pjax::begin(['id' => 'pjax_catalog']) ?>
<?php Pjax::end() ?>

<?php
$urlCatalog=Url::toRoute(['category']);
$js = <<<JS
console.log('test2');
$.pjax.reload({container: "#pjax_catalog", async: false, url:"$urlCatalog"});
    $(document).ready ( function(){
        console.log('test');
        $.pjax.reload({container: "#pjax_catalog", async: false, url:"$urlCatalog"});
    })
JS;

$this->registerJs($js);
?>