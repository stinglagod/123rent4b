<?php

namespace backend\controllers\admin;

use yii\web\Controller;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}