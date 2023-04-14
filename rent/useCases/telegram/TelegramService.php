<?php

namespace rent\useCases\telegram;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\SystemCommands\Generic2Command;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use yii\helpers\Url;

class TelegramService
{
    private ?Telegram $telegram=null;

    public function __construct(
    )
    {
        try {
            $this->telegram=new Telegram(\Yii::$app->params['telegram_botApiKey'],\Yii::$app->params['telegram_username']);
        } catch (\RuntimeException $e) {

        }

    }

    /**
     * Настраиваем webhook для Телеграмм.
     * Отправляем в Телеграм, наши данные и указываем webhook, на который мы будем принимать сообщения
     * @return bool
     */
    public function setWebhook(?string $url=null):bool
    {
        try {
            $url=$url??$this->getWebhookUrl();
            // Set webhook
            $result = $this->telegram->setWebhook($url);
            if ($result->isOk()) {
                echo $result->getDescription().PHP_EOL;
            }
            return true;
        } catch (TelegramException $e) {
            // log telegram errors
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Обрабатываем вебхук Телеграма
     * @throws TelegramException
     */
    public function hookHandle()
    {

//        $this->telegram->enableAdmins($config['admins']);

        // Add commands paths containing your custom commands
        $result=$this->telegram->addCommandsPaths([\Yii::getAlias('@rent/TelegramBots/support/')]);
        $this->recLog(print_r($result,true));
        $this->telegram->handle();
//        $commands= new GenericCommand($this->telegram);
////        \Yii::error($commands->getName());
////        \Yii::error($commands->getMessage());
////        $this->recLog($commands->getName());
//
//        $message=$commands->getMessage();
////        $this->recLog($message->getText());
//        $this->recLog( print_r($message,true));
    }

###
    private function getWebhookUrl():string
    {
        return \Yii::$app->params['telegram_hookUrl'];
//        return \Yii::$app->params['apiHostInfo'] . Url::to(['hooks/telegram/handle']);
    }

    private function recLog(?string $log=null)
    {
        $log = date('Y-m-d H:i:s ') . $log;
        file_put_contents(__DIR__ . '/log.log', $log . PHP_EOL, FILE_APPEND);
    }

}