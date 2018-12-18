<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.12.2018
 * Time: 10:58
 */
/* @var $this yii\web\View */
/* @var $model \common\models\Movement*/
?>
<?php
Modal::begin([
    'header' => '<h4 id="modalTitle"><h4>Календарь</h4>',
    'id' => 'modal',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
]);
?>
<div id='mainModalContent'>
<!--    --><?//= $this->render('_form', [
//        'model' => $model,
//    ]) ?>
    <?php
        $events = array();
        //Testing
        $Event = new \yii2fullcalendar\models\Event();
        $Event->id = 1;
        $Event->title = 'Приход';
        $Event->start = date('Y-m-d\TH:i:s\Z');
        $Event->nonstandard = [
            'field1' => 'Something I want to be included in object #1',
            'field2' => 'Something I want to be included in object #2',
        ];
        $events[] = $Event;

        $Event = new \yii2fullcalendar\models\Event();
        $Event->id = 2;
        $Event->title = 'Заказ #2';
        $Event->start = date('Y-m-d\TH:i:s\Z',strtotime('tomorrow 6am'));
        $events[] = $Event;

    ?>

    <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
      'events'=> $events,
  ));?>
</div>
<?php
Modal::end();
?>
