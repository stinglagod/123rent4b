<?php

namespace backend\controllers;

use common\models\Action;
use common\models\Movement;
use common\models\Order;
use common\models\OrderProductBlock;
use common\models\Product;
use common\models\Service;
use common\models\Status;
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
            $orderBlock_id=0;
            $out='Ошибка. Не найдена позиция для удаления';
            $session->setFlash('error', $out);
        }

        if ($orderBlock_id) {
            $block=$model->order->getOrderProductsByBlock($orderBlock_id);

            return $this->render('../order/_gridOrderProduct', [
                'dataProvider'=>$block[$orderBlock_id]['dataProvider'],
                'orderBlock_id'=>$orderBlock_id,
            ]);
        } else {
            $order=Order::findOne($order_id);
            $dataProviderService=new ActiveDataProvider([
                'pagination' => [
                    'pageSize' => 10,
                ],
                'query' => $order->getServicesQuery(),
            ]);
            return $this->render('../order/_services',[
                'services'=>$order->getServices(),
                'dataProviderService'=>$dataProviderService,
            ]);
        }

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

            //для зависимых услуг ставим флаг, что отредактировано рукаи, автоматичекски редактировать нельзя
            if (($model->service_id)and($model->service->is_depend)) {
                $model->status_id=Status::SMETA;
            }

            if ($model->save()) {
                $output='';
            } else {
                $output=$model->firstErrors;
                $session = Yii::$app->session;
                $session->setFlash('error', $output);
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
            $action_id=$post['operation'];
            if ($value>0) {
                if (!($orderProduct->addMovement($post['operation'],$value))) {
                    $session->setFlash('error', 'Ошибка добавлении движения товара');
                    return ['status' => 'error', 'out' => 'Ошибка добавлении движения товара' ];
                }
            }

        }
        $session->setFlash('success', 'Успешно сохранено');
        return ['status' => 'success', 'out' => 'Успешно сохранено' ];
    }

    public function actionTest()
    {
//        $result = Yii::$app->db->createCommand('SELECT sum(cost*qty*IFNULL(period,1)) as summ FROM `order_product` WHERE `is_montage` = 1 and `order_id`=:order_id')
//            ->bindValue(':order_id', 6)
//            ->queryOne();

//        $result = OrderProduct::find()->where(['order_id'=>6,'service_id'=>1])->count();

        $result = $orderProductDependService=OrderProduct::find()->where(['service_id'=>1,'order_id'=>24])->one();
        $result = $dependService=Service::getDependService();
        print_r($result);

    }
}
