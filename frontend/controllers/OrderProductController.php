<?php

namespace frontend\controllers;

use common\models\Action;
use common\models\Movement;
use common\models\Order;
use common\models\OrderProductBlock;
use common\models\Product;
use common\models\Service;
use common\models\Status;
use Yii;
use common\models\OrderProduct;
//use backend\models\OrderProductSearch;
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
            $order=$model->order;

            if ($model->delete()) {
                $out='Позиция заказа удалена';
                $session->setFlash('success', $out);
//                return ['status' => 'success','data'=>$out];
            } else {
                $out='Ошибка при удалении позиции заказа';
                $session->setFlash('error', $out);
            }

            $dataProvider = new ActiveDataProvider([
                'pagination' => [
                    'pageSize' => 10,
                ],
                'query' => $order->getOrderProducts(),
            ]);
            return $this->render('../order/cart/cart',[
                'order'=>$order,
                'dataProvider'=>$dataProvider
            ]);

        } else {
            $out='Ошибка. Не найдена позиция для удаления';
            $session->setFlash('error', $out);
        }

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

            //меняем только определенные аттрибуты
            switch ($attr) {
                case 'qty':
                    $model->qty=Yii::$app->request->post('OrderProduct')[0]['qty'];
                    break;
                case 'period':
                    $model->period=Yii::$app->request->post('OrderProduct')[0]['period'];
                    break;
            }
            //для зависимых услуг ставим флаг, что отредактировано рукаи, автоматичекски редактировать нельзя
            if (($model->service_id)and($model->service->is_depend)) {
                $model->status_id=Status::SMETA;
            }

            if ($model->save()) {
                $output='';
                $message='';
//                $output=Yii::$app->request->post('OrderProduct')[0]['qty'];
            } else {
                $output='';
                $message=$model->getErrors('')[0];
                $session = Yii::$app->session;
                $session->setFlash('error', $message);
            }

            $out = Json::encode(['output'=>$output, 'message'=>$message]);
            return $out;
        }
    }


}
