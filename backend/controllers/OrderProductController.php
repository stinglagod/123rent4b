<?php

namespace backend\controllers;

use common\models\Order;
use common\models\OrderProductBlock;
use Yii;
use common\models\OrderProduct;
use backend\models\OrderProductSearch;
use yii\data\ActiveDataProvider;
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
    public function actionDeleteAjax($id)
    {
        $session = Yii::$app->session;

        if ($model=$this->findModel($id)) {
            $order_id=$model->order_id;
            $orderBlock_id=$model->orderBlock_id;
            if ($model->delete()) {
                $out='Позиция заказа удалена';
                $session->setFlash('success', $out);
//                return ['status' => 'success','data'=>$out];
            } else {
                $out='Ошибка при удалении позиции заказа';
                $session->setFlash('error', $out);
            }
        } else {
            $order_id=0;
            $out='Ошибка. Не найдена позиция для удаления';
            $session->setFlash('error', $out);
        }

        $query=OrderProduct::find()->where(['order_id'=>$order_id])->indexBy('id');
        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query,
        ]);
        return $this->render('../order/_gridOrderProduct', [
            'dataProvider'=>$dataProvider,
            'orderBlock_id'=>$orderBlock_id,
        ]);
//            return ['status' => 'error','data'=>$out];
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
            $model = OrderProduct::findOne($id);
            $attr=Yii::$app->request->post('editableAttribute');
            $model->$attr=Yii::$app->request->post($attr);

            if ($model->save()) {
                $output='';
            } else {
                $output=$model->firstErrors;
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

    public function actionTest()
    {
        $model=Order::findOne(5);
//        $response='<pre>';
////        $test=OrderProduct::find()->where(['order_id'=>5])->indexBy(['orderProductBlock_id'])->all();
////        $test=OrderProduct::find()->where(['order_id'=>5])->asArray()->all();
//        $test=OrderProduct::find()->where(['order_id'=>5])->with(['orderProductBlock'])->all();
//        $test2=array();
//        foreach ($test as $item) {
//            $test2[$item->orderProductBlock_id][]=$item;
//        }


//        $response.='</pre>';
        echo "<pre>";
        var_dump($model->getOrderProductsByBlock());
        echo "<br>";
        echo "</pre>";
//        return var_dump($test2[1][0]);
//        return $response;

    }
}
