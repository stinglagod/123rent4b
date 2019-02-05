<?php

namespace backend\controllers;

use common\models\Category;
use common\models\Ostatok;
use common\models\ProductAttribute;
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
//    public function actionIndex()
//    {
//        $searchModel = new ProductSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

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
    public function actionUpdate($id,$category=null,$edit=false)
    {
        if (($model = Product::findOne($id)) === null) {
            return $this->renderAjax('../category/_404', []);
        }
        $session = Yii::$app->session;
//        if (Yii::$app->request->isAjax) {
//            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        }
        $post=Yii::$app->request->post();
        if ($model->load($post)) {
//            TODO: Почему-то при js серализации не записываются массив категорий. Пришлось руками все запихивать. Ндо бы исправить
            $model->setCategoriesArray($post['Product']['categoriesArray']);
            $model->setTagsArray($post['Product']['tagsArray']);
            $model->save();

            $session->setFlash('success1', $model->isNewRecord?'Товар добавлен.':'Товар отредактирован.');
            return $this->renderAjax('_form', [
                'model' => $model,
                'category' => $category,
                'edit'=>$edit,
            ]);
//            return ['out' => $model, 'status' => 'success'];
        }

//        return $this->renderAjax('_form', [
        return $this->renderAjax('_form', [
            'model' => $model,
            'category' => $category,
            'edit'=>$edit,
        ]);

//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('_form', [
//            'model' => $model,
//            'category' => $category,
//            'edit' => $edit,
//        ]);
    }

    /**
     * Отображение товара с помощью аякса
     * @param null $id
     * @param null $category
     * @param bool $edit
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdateAjax($id=null,$category=null,$edit=false)
    {
        if (!(Yii::$app->request->isPjax)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
//        return print_r($category);
//      TODO: А что делать если категория не найдена?
        if ($category) {
            $category=Category::findCategory($category);
        }


        if ($id) {
            if (($model = Product::findOne($id)) === null) {
                return $this->renderAjax('../category/_404', [
                ]);
            }
        } else {
            $model= new Product();
        }

        $session = Yii::$app->session;

        if ($model->isNewRecord){
            $model->setCategoriesArray($category->id);
//            TODO: Сделано так, что бы сразу можно было загружать изображения в товар.
            $edit=true;
            $model->save();
        }
        $post=Yii::$app->request->post();

        if ($model->load($post)) {

//            TODO: Почему-то при js серализации не записываются массив категорий. Пришлось руками все запихивать. Ндо бы исправить
            $model->setCategoriesArray($post['Product']['categoriesArray']);
            $model->setTagsArray($post['Product']['tagsArray']);
            $model->save();

            $session->setFlash('success1', $model->isNewRecord?'Товар добавлен.':'Товар отредактирован.');
//            return $this->renderAjax('_form', [
//                'model' => $model,
//                'category' => $category,
//                'edit'=>$edit,
//            ]);
        }

        return $this->renderAjax('_form', [
//            return $this->render('_form', [
            'model' => $model,
            'category' => $category,
            'edit'=>$edit,
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

    /**
     * @param Product $model
     * @return ProductAttribute[]
     */
    private function initProductAttributes(Product $model)
    {
        /** @var ProductAttribute[] $productAttributes*/
        $productAttributes = $model->getProductAttributes()->with('prodAttribute')->indexBy('attribute_id')->all();
        $attributes = Attribute::find()->indexBy('id')->all();

        foreach (array_diff_key($attributes,$productAttributes) as $attribute) {
            $productAttributes[$attribute->id] = new ProductAttribute(['attribute_id' => $attribute->id]);
        }

        foreach ($productAttributes as $productAttribute) {
            $productAttribute->setScenario(ProductAttribute::SCENARIO_TABULAR);
        }
        return $productAttributes;
    }

    /**
     * @param ProductAttribute[] $productAttributes
     * @param Product $model
     */
    private function processProductAttributes($productAttributes, Product $model)
    {
        foreach ($productAttributes as $productAttribute) {
            $productAttribute->product_id= $model->id;
            if ($productAttribute->validate()) {
                if (!empty($productAttribute->value)) {
                    $productAttribute->save();
                } else {
                    $productAttribute->delete();
                }
            }
        }
    }
}
