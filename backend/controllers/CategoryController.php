<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use common\models\Category;
use backend\models\CategorySearch;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ProductCategory;
use common\models\Product;


use creocoder\nestedsets\NestedSetsBehavior;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewAjax($id)
    {
        $model=$this->findModel($id);
        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            // read your posted model attributes
            if ($model->load($_POST)) {
                $session = Yii::$app->session;
                if ($model->save()) {
                    $session->setFlash('success', 'Каталог успешно сохранен');
                    $value = $model->name;
                    return ['output'=>$value, 'message'=>''];

                } else {
                    $session->setFlash('error', 'Ошибка при сохранении каталога');
                }
            }
        } else {
//          Ищем товары данного раздела

            $productsQuery = Product::find();
            $productCategories=ProductCategory::find()->select(['product_id'])->where(['category_id' => $id])->orderBy('product_id')->asArray()->column();;
            $productsQuery->where(['id' => $productCategories]);

            $productsDataProvider = new ActiveDataProvider([
                'query' => $productsQuery,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $this->renderAjax('view', [
                'model' => $model,
                'productsDataProvider' => $productsDataProvider
            ]);
        }

    }
    public function actionAddAjax($parent=0)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new Category();
        $session = Yii::$app->session;

        $model->name="(не указан)";
        $model->client_id=1;

        if ($parent==0) {
            $parent=$model->root;
        } else {
            $parent = Category::find()->andWhere(['id'=>$parent])->one();
        }

//        $model->prependTo($parent);
        $model->appendTo($parent);

        if ( $model->save()) {
            $session->setFlash('success', 'Раздел создан');
            return ['out' => $model, 'status' => 'success'];
        } else {
            $session->setFlash('error', 'Ошибка при создании раздела');
            return ['out' => $model->errors, 'status' => 'error'];
        }
    }
    public function actionDelAjax($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model= Category::find()->andWhere(['id'=>$id])->one();
        $session = Yii::$app->session;

        if ($model->delete()) {
            $session->setFlash('success', 'Раздел удален');
            return ['out' => $model, 'status' => 'success'];
        } else {
            $session->setFlash('error', 'Ошибка при удалении раздела');
            return ['out' => $model->errors, 'status' => 'error'];
        }
    }


    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $parent='';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->sub == null ) {
                if (!($parent=$model->root)) {
                    $parent = new Category(['name' => 'Корень','client_id'=>1]);
                    $parent->makeRoot();
                }
            } else {
                $parent = Category::find()->andWhere(['id'=>$model->sub])->one();
            }
            $model->prependTo($parent);

            if ( $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('create', [
            'model' => $model,
            'parent' => $parent
        ]);

    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTree($id=0)
    {
//        $countries = new Category(['name' => 'Countries','client_id' => 1]);
//        $countries->makeRoot();
//        $russia = new Category(['name' => 'Russia','client_id' => 1]);
//        $russia->prependTo($countries);

        $id=$id?$id:(Category::getRoot()->id);
        return print_r($id);
        return $this->render('tree', [
            'data' => Category::findOne($id)->tree(),
        ]);
    }

    public function actionMove($item, $action, $second)
    {
        /**
         * @var $item_model NestedSetsBehavior
         * @var $second_model NestedSetsBehavior
         */
        $item_model = Category::findOne($item);
        $second_model = Category::findOne($second);

        $session = Yii::$app->session;

        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;

        if (($second_model->isRoot())or ($item_model->isRoot())) {
            $session->setFlash('error', 'Перемещать (в,над, под) корень зарещено');
            return ['out' => "", 'status' => 'error'];
        }

        switch ($action) {
            case 'after':
                $item_model->insertAfter($second_model);
                break;
            case 'before':
                $item_model->insertBefore($second_model);
                break;
            case 'over':
                $item_model->appendTo($second_model);
                break;
        }

        if ($item_model->save()) {
            $session->setFlash('success', 'Каталог успешно перемещен');
            $out="Каталог успешно перемещен";
            $status="success";
        } else {
            $out=$item_model->errors;
            $status="error";
        }
        return ['out' => $out, 'status' => $status];
    }
    /**
     * Returns IDs of $category and all its sub-$categories
     *
     * @param Category[] $categories all categories
     * @param int $categoryId id of productGroup to start search with
     * @param array $categoryIds
     * @return array $categoryIds
     */
    private function getCategoryIds($categories, $categoryId, &$categoryIds = [])
    {
        foreach ($categories as $category) {
            if ($category->id == $categoryId) {
                $categoryIds[] = $category->id;
            }
            elseif ($category->parent_id == $categoryId){
                $this->getCategoryIds($categories, $category->id, $categoryIds);
            }
        }
        return $categoryIds;
    }
}
