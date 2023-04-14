<?php

namespace console\controllers;

use rent\useCases\telegram\TelegramService;
use yii\console\Controller;

class TelegramController extends Controller
{
    private TelegramService $telegramService;

    public function __construct($id, $module, TelegramService $telegramService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->telegramService = $telegramService;
    }

    /**
     * Настраиваем webhook для Телеграмм. Отправляем в Телеграм, наши данные и указываем webhook, на который мы будем принимать сообщения
     */
    public function actionSetup(?string $url=null)
    {
        $this->telegramService->setWebhook($url);
    }
}