<?php
use kartik\editable\Editable;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 01.03.2019
 * Time: 11:09
 */
/* @var $parent_id integer */
/* @var $orderBlock_id integer */
$orderProductsBySet=\common\models\OrderProduct::find()->where(['parent_id'=>$parent_id])->andWhere(['orderBlock_id'=>$orderBlock_id])->with(['product'])->all();
?>
<div class="row">
    <div class="col-md-4">
        Состав:
    </div>
    <div class="col-md-8">
        <div class="btn-group pull-right" role="group" aria-label="toolbar">
            <button type="button" class="btn btn-success lst_addproduct" data-block_id="<?=$orderBlock_id?>" data-parent_id="<?=$parent_id?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-condensed table-hover small kv-table">
            <tbody>
            <tr class="success">
                <th class="text-center text-success">Продукт</th>
                <th class="text-center text-success">Цена</th>
                <th class="text-center text-success">Кол-во</th>
                <th class="text-center text-success">Период</th>
<!--                <th class="text-center text-success">Сумма</th>-->
                <th class="text-center text-success">Статус</th>
                <th class="text-center text-success">Действия</th>
            </tr>
            <?php foreach ($orderProductsBySet as $item) {
                if ($item->type ==\common\models\OrderProduct::COLLECT) {
                    continue;
                }
                ?>
                <tr>
                    <td class="text-center" ><?=Html::a(Html::encode($item->product->name), $item->product->getUrl(),[
                            'data-pjax'=>0,
                            'class'=>'popover-product-name',
                            'data-content'=> '<img src="'.$item->product->getThumb(\common\models\File::THUMBMIDDLE).'"/>',
                        ]);?></td>
                    <td class="text-center">
                        <?=$item->cost?>
<!--                        --><?//=Editable::widget([
//                        'name'=>'person_name',
//                        'asPopover' => true,
//                        'value' => $item->cost,
//                        'header' => 'Цена',
//                        'size'=>'md',
//                        'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
//                        ]);?>
                    </td>
                    <td class="text-center">
                        <?php
                        $editable = Editable::begin([
                            'header' => Yii::t('app', 'Количество'),
                            'name'=>'qty',
                            'value' => $item->qty,
                            'size' => 'md',
                            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                            'options' => [
                                'pluginOptions' => ['min' => 0, 'max' => 5000]
                            ],
                            'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$item->id]) ],
                        ]);
                        $form = $editable->getForm();
                        echo \yii\helpers\Html::hiddenInput('editableAttribute', 'qty');
                        Editable::end()
                        ?>
                    </td>
                    <td class="text-center"><?=$item->period?></td>
<!--                    <td class="text-center">--><?//=($item->type==\common\models\OrderProduct::RENT)?$item->qty*$item->cost*$item->period:$item->qty*$item->cost?><!--</td>-->
                    <td class="text-center">
                        <?php
                            $status=$item->getStatus();
                            echo $status['text'];
                        ?>
                    </td>
                    <td class="text-center">
                        <?=
                        \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', \yii\helpers\Url::toRoute(['order-product/delete-ajax','id'=>$item->id]), [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '#pjax_order-product_grid_'.$item->id,
                            'data-confirm'=>'Вы действительно хотите удалить позицию из заказа?',
                            'data-method'=>'post'
                        ]);
                        ?>
                    </td>
                </tr>
            <?php } ?>

            </tbody></table>
    </div>
</div>
