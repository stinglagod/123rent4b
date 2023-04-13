<?php
use rent\entities\Support\Task\Comment;
use rent\entities\Support\Task\Task;
use rent\helpers\TextHelper;

/* @var $this yii\web\View */
/* @var $model Comment */
/* @var $task Task */

//если чужое сообщение тогда имя, аватарка слава, дата справа
if ($itIsMy=$model->itIsMy(\Yii::$app->user->getId())) {
    $position=['left','right','right'];

} else {
    $position=['right','left',''];
}

?>
<!-- Message. Default to the left -->
<div class="direct-chat-msg <?=$position['2']?>">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-<?=$position[1]?>"><?=$model->author_name?></span>
        <span class="direct-chat-timestamp pull-<?=$position[0]?>">
            <?=TextHelper::getDateTimeHumanWithTimezone($model->created_at)?>
        </span>
    </div>
    <!-- /.direct-chat-info -->
    <img class="direct-chat-img" src="<?=$model->author->getAvatarUrl()?>" alt="<?=$model->author_name?>"><!-- /.direct-chat-img -->
    <div class="direct-chat-text  <?=$itIsMy?'bg-light-blue':''?>">
        <?=str_replace(array("\r\n", "\r", "\n"), '<br>', $model->message)?>
        <?if ($model->files) :?>
            <label>Файлы:</label><br>
            <? foreach ($model->files as $file) : ?>
                <a href="<?=$file->getUrl()?>" class="bg-light-blue" target="_blank"><?=$file->file?></a> <br>
            <?endforeach;?>
        <?endif;?>
    </div>
    <!-- /.direct-chat-text -->
</div>
<!-- /.direct-chat-msg -->