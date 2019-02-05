<?php

namespace backend\controllers;

use Yii;
use common\models\ProductAttribute;
use backend\models\ProductAttributeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductAttributeController implements the CRUD actions for ProductAttribute model.
 */
class ProductAttributeController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all ProductAttribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductAttributeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductAttribute model.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return mixed
     */
    public function actionView($product_id, $attribute_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($product_id, $attribute_id),
        ]);
    }

    /**
     * Creates a new ProductAttribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductAttribute();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_id' => $model->product_id, 'attribute_id' => $model->attribute_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductAttribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return mixed
     */
    public function actionUpdate($product_id, $attribute_id)
    {
        $model = $this->findModel($product_id, $attribute_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_id' => $model->product_id, 'attribute_id' => $model->attribute_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductAttribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return mixed
     */
    public function actionDelete($product_id, $attribute_id)
    {
        $this->findModel($product_id, $attribute_id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductAttribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return ProductAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_id, $attribute_id)
    {
        if (($model = ProductAttribute::findOne(['product_id' => $product_id, 'attribute_id' => $attribute_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
