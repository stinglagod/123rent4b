<?php

namespace rent\useCases\telegram;

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
     * Настраиваем webhook для Телеграмм
     * Отправляем в Телеграм, наши данные и указываем webhook, на который мы будем принимать сообщения
     * @return bool
     */
    public function setWebhook():bool
    {
        try {
            // Set webhook
            $result = $this->telegram->setWebhook($this->getWebhookUrl());
            if ($result->isOk()) {
                echo $result->getDescription();
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
        $this->telegram->handle();
    }

###
    private function getWebhookUrl():string
    {
        return \Yii::$app->params['apiHostInfo'] . Url::to(['hooks/telegram/handle']);
    }


}