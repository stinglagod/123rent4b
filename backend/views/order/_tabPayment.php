<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 24.12.2018
 * Time: 23:32
 */?>

<div class="row">
    <div class="col-md-12">
        <div class="btn-group pull-right" role="group" aria-label="toolbar">
            <button type="button" class="btn btn-success lst_addCash" title="Добавить платеж" data-order_id="<?=$model->id?>">Добавить платеж</button>
        </div>
    </div>
</div>
<?=
    $this->render('../cash/_grid', [
        'dataProvider' => $dataProviderCash,
    ]);
?>
