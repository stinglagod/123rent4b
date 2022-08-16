<?php

namespace backend\controllers\shop;

use backend\forms\Shop\PaymentSearch;
use rent\entities\Shop\Order\Payment;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\useCases\manage\Shop\PaymentManageService;
use Yii;
use yii\web\Controller;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class CashboxController extends Controller
{
    private PaymentManageService $service;

    public function __construct($id, $module, PaymentManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
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

    /**
     * @return mixed
     */
    public function actionCreatePlus()
    {
        $form = new PaymentForm();
        $form->purpose_id=Payment::POP_INCOMING;
        $form->type_id=Payment::TYPE_CASH;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $payment = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create-plus', [
            'model' => $form,
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreateMinus()
    {
        $form = new PaymentForm();
        $form->purpose_id=Payment::POP_REFUND;
        $form->type_id=Payment::TYPE_CASH;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $payment = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create-minus', [
            'model' => $form,
        ]);
    }
}