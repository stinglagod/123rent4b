<?php

namespace backend\controllers\shop;

use backend\forms\Shop\PaymentSearch;
use rent\entities\Shop\Order\Payment;
use rent\forms\manage\Shop\Order\PayerForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\readModels\Shop\PaymentReadRepository;
use rent\useCases\manage\Shop\PaymentManageService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class CashboxController extends Controller
{
    private PaymentManageService $service;
    private PaymentReadRepository $readRepository;

    public function __construct($id, $module, PaymentManageService $service, PaymentReadRepository $readRepository, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->readRepository = $readRepository;
    }
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $balance=$this->readRepository->balanceByDate(time());
//        $balance=$this->readRepository->balanceByDate(time());
//        $balance=$this->readRepository->balanceByDate(time());
//        $balance=$this->readRepository->balanceByDate(time());
        $balances=$this->readRepository->balancesByDate(time());


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'balances' => $balances,
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

    /**
     * @return mixed
     */
    public function actionCreateCorrect()
    {
        $form = new PaymentForm();
        $form->purpose_id=Payment::POP_CORRECT;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $payment = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create-correct', [
            'model' => $form,
        ]);
    }
    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Payment
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}