<?php

namespace backend\controllers;

use common\models\Ostatok;
use common\models\User;
use Yii;
use common\models\Product;
use backend\models\Product as ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
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
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionUpdateAjax($id=null,$category=null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $id?($this->findModel($id)):new Product();
        $session = Yii::$app->session;
        if ($model->isNewRecord){
            $model->setCategoriesArray($category);
        }

        if ($model->load(Yii::$app->request->post())) {
//            TODO: Почему-то при js серализации не записываются массив категорий. Пришлось руками все запихивать. Ндо бы исправить
            $post=Yii::$app->request->post();
            $model->setCategoriesArray($post['Product']['categoriesArray']);
            $model->setTagsArray($post['Product']['tagsArray']);
            $model->save();

            $session->setFlash('success1', $model->isNewRecord?'Товар добавлен.':'Товар отредактирован.');
            return $this->renderAjax('_form', [
                'model' => $model,
                'category' => $category
            ]);
//            return ['out' => $model, 'status' => 'success'];
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'category' => $category
        ]);
    }

    public function actionCalendarAjax($product_id,$start=NULL,$end=NULL,$_=NULL){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        /** @var  $times Ostatok[]*/
        $times = Ostatok::find()
            ->where(['client_id'=> User::findOne(\Yii::$app->user->id)->client_id])
            ->andWhere(['product_id'=>$product_id])
            ->andWhere(['between', 'dateTime', $start, $end ])
            ->all();

        $events = array();

        foreach ($times AS $time){
            //Testing
            $Event = new \yii2fullcalendar\models\Event();
            $Event->id = $time->id;
            $Event->title = $time->categoryAsString;
            $Event->start = date('Y-m-d\TH:i:s\Z',strtotime($time->dateTime));
            $Event->end = date('Y-m-d\TH:i:s\Z',strtotime($time->dateTime));
            $events[] = $Event;
        }

        return $events;
    }
    public function actionModalCalendar($id=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

//        $searchModel = new MovementSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
        $data=$this->renderAjax('_modalCalendar');
        return ['status' => 'success','data'=>$data];
    }
}
