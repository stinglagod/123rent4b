<?php

namespace backend\controllers;

use Yii;
use common\models\Movement;
use backend\models\MovementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * MovementController implements the CRUD actions for Movement model.
 */
class MovementController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all Movement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MovementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Movement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Movement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Movement();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Movement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
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
     * Deletes an existing Movement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $product_id=$model->product_id;
        $model->delete();
        if (Yii::$app->request->isAjax){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderList($product_id);
        } else {
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the Movement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Movement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Movement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateAjax($product_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $searchModel = new MovementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
        $data=$this->renderAjax('_modalForm',[
            'product_id'=>$product_id,
            'grid'=>self::renderList($product_id),
        ]);
        return ['status' => 'success','data'=>$data];
    }
    public function actionAddAjax($product_id,$action_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        /** @var $model Movement $model */
        $model=new Movement();
        $model->action_id=$action_id;
        $model->product_id=$product_id;
        if ($model->save()) {
            return $this->renderList($product_id);
//            return ['status' => 'success','data'=>$model];
        } else {
            return ['status' => 'error','data'=>$model->firstErrors];
        }
    }
    public function actionIndexPjax($product_id=null)
    {
// validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            $model = Movement::findOne(Yii::$app->request->post('editableKey'));
            // fetch the first entry in posted data (there should only be one entry
            // anyway in this array for an editable submission)
            $posted = current($_POST['Movement']);
            $post = ['Movement' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output='';
            }

            $out = Json::encode(['output'=>$output, 'message'=>'']);
            return $out;
        }
        return $this->renderList($product_id);
    }

    protected function renderList($product_id=null)
    {
        $searchModel = new MovementSearch();
        $query = Movement::find()->orderBy('dateTime');
        if ($product_id) {
            $query=$query->where(['product_id'=>$product_id]);
        }
        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 5,
            ],
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['dateTime' => SORT_ASC]
            ]
        ]);

        $dataProvider->sort->route = Url::toRoute(['index']);

        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$method('_grid', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
