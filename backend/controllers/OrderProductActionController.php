<?php

namespace backend\controllers;

use Yii;
use common\models\OrderProductAction;
use backend\models\OrderProductActionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderProductActionController implements the CRUD actions for OrderProductAction model.
 */
class OrderProductActionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrderProductAction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderProductActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderProductAction model.
     * @param integer $order_product_id
     * @param integer $movement_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($order_product_id, $movement_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($order_product_id, $movement_id),
        ]);
    }

    /**
     * Creates a new OrderProductAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderProductAction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'order_product_id' => $model->order_product_id, 'movement_id' => $model->movement_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrderProductAction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $order_product_id
     * @param integer $movement_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($order_product_id, $movement_id)
    {
        $model = $this->findModel($order_product_id, $movement_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'order_product_id' => $model->order_product_id, 'movement_id' => $model->movement_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrderProductAction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $order_product_id
     * @param integer $movement_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($order_product_id, $movement_id)
    {
        $this->findModel($order_product_id, $movement_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrderProductAction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $order_product_id
     * @param integer $movement_id
     * @return OrderProductAction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_product_id, $movement_id)
    {
        if (($model = OrderProductAction::findOne(['order_product_id' => $order_product_id, 'movement_id' => $movement_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
