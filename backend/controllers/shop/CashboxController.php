<?php

namespace backend\controllers\shop;

use backend\forms\Shop\PaymentSearch;
use Yii;
use yii\web\Controller;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class CashboxController extends Controller
{
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}