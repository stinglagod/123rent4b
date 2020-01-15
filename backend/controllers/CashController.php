<?php

namespace backend\controllers;

use common\models\Order;
use common\models\OrderCash;
use Yii;
use common\models\Cash;
use backend\models\CashSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * CashController implements the CRUD actions for Cash model.
 */
class CashController extends Controller
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
     * Lists all Cash models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cash model.
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
     * Creates a new Cash model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cash();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cash model.
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
     * Deletes an existing Cash model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $order_id=$model->orders[0]->id;
        $model->delete();
        if (Yii::$app->request->isAjax){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderList($order_id);
        } else {
            return $this->redirect(['index']);
        }

    }

    protected function renderList($order_id=null)
    {
        $query = Cash::find();
        //провайдер платежей
        if ($order_id) {
            $сashIds=OrderCash::find()->select('cash_id')->where(['order_id'=>$order_id])->asArray()->column();
            $query=$query->where(['in', 'id', $сashIds]);
        }

        $query=$query->orderBy('dateTime');

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query,
        ]);

        $dataProvider->sort->route = Url::toRoute(['index']);

        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$method('_grid', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Finds the Cash model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cash the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cash::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionUpdateAjax($order_id, $id=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;

        $order=Order::findOne($order_id);

        if (Yii::$app->request->post('hasEditable')) {
            $model = Cash::findOne(Yii::$app->request->post('editableKey'));

            $posted = current($_POST['Cash']);
            $post = ['Cash' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output='';
            }
            $out = ['output'=>$output, 'message'=>''];
            return $out;
        }

        if (empty($id)) {
            $model = new Cash();
        } else {
            $model = $this->findModel($id);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $order->link('cashes',$model);
                $session->setFlash('success', 'Платеж добавлен');
                return ['out' => $model, 'status' => 'success'];
            } else {
                $session->setFlash('error', 'Ошибка при сохранении платежа');
                return ['out' => 'Ошибка при сохранении платежа', 'status' => 'error'];
            }

        }
        return ['status' => 'error','data'=>'Что-то пошло не так'];
    }
}
