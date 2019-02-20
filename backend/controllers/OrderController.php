<?php

namespace backend\controllers;

use backend\models\Product;
use common\models\Movement;
use common\models\OrderProduct;
use common\models\OrderProductAction;
use Yii;
use common\models\Order;
use backend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

//    public function actionCreateAjax()
//    {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $model = new Order();
//        $session = Yii::$app->session;
//
//        if ($model->load(Yii::$app->request->post())) {
//            if ($model->save()) {
//                $session->setFlash('success', 'Новый заказ создан');
//                $session['activeOrderId'] = $model->id;
//                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
//                return ['out' => $model, 'status' => 'success','data'=>$data];
//            } else {
//                $session->setFlash('error', 'Ошибка при создании нового заказа');
//                return ['out' => 'Ошибка при создании нового заказа', 'status' => 'error'];
//            }
//
//        }
//        $data=$this->renderAjax('_modalForm',['order'=>$model]);
//        return ['status' => 'success','data'=>$data];
//    }

    public function actionUpdateAjax($id=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->post('hasEditable')) {
            $model = OrderProduct::findOne(Yii::$app->request->post('editableKey'));
            // fetch the first entry in posted data (there should only be one entry
            // anyway in this array for an editable submission)
            $posted = current($_POST['OrderProduct']);
            $post = ['OrderProduct' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output='';
            }
            $out = ['output'=>$output, 'message'=>''];
            return $out;
        }

        if (empty($id)) {
            $model = new Order();
        } else {
            $model = $this->findModel($id);
        }
        $session = Yii::$app->session;



        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $session->setFlash('success', 'Заказ сохранен');
                $session['activeOrderId'] = $model->id;
                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
                return ['out' => $model, 'status' => 'success','data'=>$data];
            } else {
                $session->setFlash('error', 'Ошибка при сохранении заказа');
                return ['out' => 'Ошибка при сохранении заказа', 'status' => 'error'];
            }

        }
        $data=$this->renderAjax('_modalForm',['order'=>$model]);
        return ['status' => 'success','data'=>$data];
    }
//    выводим индекс в аякcе
    public function actionIndexAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orders=Order::getActual();
        $session = Yii::$app->session;

        $post=Yii::$app->request->post();
        if ($activeOrderId=$post['activeId']) {
            $session['activeOrderId'] = $activeOrderId;
        }
        $data=$this->renderAjax('_orderHeaderBlock',['orders'=>$orders]);
        return ['status' => 'success','data'=>$data];

    }
    //    Добавляем в заказ товар в аяксе
    public function actionAddProductAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;
        $currentOrder=Order::getCurrent();

        $post=Yii::$app->request->post();
        if (($productId=$post['id'])and($product=\common\models\Product::findOne($productId))) {
            $qty=empty($post['qty'])?1:$post['qty'];

//          Определяем какой товар. Аренда продажа
            if (array_key_exists('pricerent',$post)) {
                $type=OrderProduct::RENT;
            } elseif(array_key_exists('pricesale',$post))  {
                $type=OrderProduct::SALE;
            } else {
                $session->setFlash('error', 'Ошибка при добавлении товара в корзину. Обратитесь к администратору. ');
                return ['status' => 'error'];
            }

            if ($currentOrder->addToBasket($productId,$qty,$type)) {
                $out='Товар добавлен в заказ';
                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
            }
        }

        if (empty($data)) {
            $session->setFlash('error', $out);
            return ['status' => 'error'];
        } else {
            $session->setFlash('success', $out);
            return ['status' => 'success','data'=>$data];
        }

    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $query=OrderProduct::find()->where(['order_id'=>$id])->indexBy('id');

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query,
//
        ]);
        $orderProductIds=OrderProduct::find()->select('id')->where(['order_id'=>$id])->asArray()->column();
//        return print_r($orderProductIds);
        $movementIds=OrderProductAction::find()->select('movement_id')->where(['in', 'order_product_id', $orderProductIds])->asArray()->column();
//        return print_r($movementIds);
        $query2 = Movement::find()->where(['in', 'id', $movementIds])->orderBy('dateTime');
//        return print_r($query2);
        $dataProviderMovement=new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query2,
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        if ((Yii::$app->request->isAjax)) {
//            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderAjax('update', [
                'model' => $model,
                'dataProvider'=>$dataProvider,
                'dataProviderMovement'=>$dataProviderMovement,
            ]);
        }
        return $this->render('update', [
            'model' => $model,
            'dataProvider'=>$dataProvider,
            'dataProviderMovement'=>$dataProviderMovement,
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
     * Удаление позиции в заказе
     * @param $orderProduct_id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteOrderProduct($orderProduct_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;

        if ($model=OrderProduct::findOne($orderProduct_id)) {
            if ($model->delete()) {
                $out='Позиция заказа удалена';
                $session->setFlash('success', $out);
                return ['status' => 'success','data'=>$out];
            } else {
                $out='Ошибка при удалении позиции заказа';
            }
        } else {
            $out='Ошибка. Не найдена позиция для удаления';
        }
        $session->setFlash('error', $out);
        return ['status' => 'error','data'=>$out];
    }
    protected function renderListOrderProduct()
    {

    }


    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * контроллер возращает содеражание модальноо окна для подверждения операции движения с товарами в заказе
     */
    public function actionContentConfirmModalAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ((isset($_POST['keylist']))and(isset($_POST['operation']))) {
            $keys=$_POST['keylist'];
            if (!is_array($keys)) {
                return ['status' => 'error','data'=>'Ошибка при получение массива отмеченных строк'];
            }
            $query = OrderProduct::find()->where(['in', 'id', $keys]);
            $dataProvider = new ActiveDataProvider([
                'pagination' => [
                    'pageSize' => 5,
                ],
                'query' => $query,
            ]);
            $data = $this->renderAjax('_modalConfirmOperation', [
                'dataProvider'=>$dataProvider,
                'operation'=>$_POST['operation']
            ]);
            return ['status' => 'success','data'=>$data];
        }
    }


}
