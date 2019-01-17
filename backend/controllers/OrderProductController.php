<?php

namespace backend\controllers;

use Yii;
use common\models\OrderProduct;
use backend\models\OrderProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * OrderProductController implements the CRUD actions for OrderProduct model.
 */
class OrderProductController extends Controller
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
     * Lists all OrderProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderProduct model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OrderProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrderProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrderProduct model.
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
     * Finds the OrderProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderProduct::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionUpdateAjax($id=null)
    {
        if (Yii::$app->request->post('hasEditable')) {
            $output='';
            $model = OrderProduct::findOne(Yii::$app->request->post('editableKey'));
            $posted = current($_POST['OrderProduct']);
            $post = ['OrderProduct' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output='';
            }

            $out = Json::encode(['output'=>$output, 'message'=>'']);
            return $out;
        }
    }

    /**
     * Добавление движения товара в заказе
     */
    public function actionMovementAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $arrQty=$post['OrderProduct']['qty'];
        $session = Yii::$app->session;
        foreach ($post['OrderProduct']['qty'] as $key => $value) {
            $orderProduct=OrderProduct::findOne($key);
            if (!($orderProduct->addMovement($post['operation'],$value))) {
                $session->setFlash('error', 'Ошибка добавлении движения товара');
                return ['status' => 'error', 'out' => 'Ошибка добавлении движения товара' ];
            }
        }
        $session->setFlash('success', 'Успешно сохранено');
        return ['status' => 'success', 'out' => 'Успешно сохранено' ];
    }
}
