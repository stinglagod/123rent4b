<?php

namespace api\controllers\hooks;

use rent\useCases\telegram\TelegramService;
use Yii;
use yii\web\Controller;

class TelegramController extends Controller
{
    private TelegramService $telegramService;

    public function __construct($id, $module, TelegramService $telegramService,$config = [])
    {
        parent::__construct($id, $module, $config);
        $this->telegramService = $telegramService;
    }

    /**
     * Обработка запросов от Телеграма
     * @return void
     */
    public function actionHandle():void
    {
        try {
            $this->telegramService->hookHandle();
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }

}