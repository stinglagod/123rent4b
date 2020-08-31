<?php

namespace backend\controllers;

use common\models\File;
use rent\entities\User\User;
use Yii;
use common\models\Category;
use backend\models\CategorySearch;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ProductCategory;
use common\models\Product;
use backend\models\ProductSearch;
use yii\helpers\Json;
use backend\models\MultipleUploadForm;


use creocoder\nestedsets\NestedSetsBehavior;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    CONST NEWNAME='Новый раздел';
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
    public function actionTree($orderblock_id=null,$parent_id=null,$dateBegin=null,$dateEnd=null)
    {
        $root=Category::getRoot()->id;
        $this->layout = 'main-catalog';
        $searchModel = new ProductSearch();
        if (empty(Yii::$app->request->queryParams['ProductSearch'])) {
            $htmRightDetail='';
        } else {
            $searchModel->search(Yii::$app->request->queryParams);
            $htmRightDetail=$this->actionViewAjax(null,null,$orderblock_id,$parent_id);
        }


        return $this->render('index', [
            'tree' => Category::findOne($root)->tree(),
            'urlRightDetail'=>'',
            'activeNode'=>'',
            'orderblock_id'=>$orderblock_id,
            'dateBegin'=>$dateBegin,
            'dateEnd'=>$dateEnd,
            'searchModel'=>$searchModel,
            'htmRightDetail'=>$htmRightDetail,
        ]);
    }
    /**
     * Главное дерево каталога
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex($alias=null, $product_id=null, $active_id=null,$orderblock_id=null,$parent_id=null,$dateBegin=null,$dateEnd=null)
    {
        $root=Category::getRoot()->id;
        $product=null;
        $category=null;
        $session = Yii::$app->session;
        $cookies = Yii::$app->request->cookies;
        $searchModel = new ProductSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $htmRightDetail=null;

        if ((Yii::$app->request->post('_pjax')=='#pjax_left-tree')) {
            return $this->render('index', [
                'tree' => Category::findOne($root)->tree(),
                'urlRightDetail'=>'',
                'searchModel'=>$searchModel,
                'activeNode'=>Yii::$app->request->post('active_id')
            ]);
        }

        if ($product_id) {

            $product=Product::findOne($product_id);
            $category=Category::findCategory($alias);
            $active_id=$category->id;
//            return $alias;
            $urlRightDetail=Url::toRoute(['product/update-ajax',
                'id'=>$product->id,
                'category'=>$category->id,
                'orderblock_id'=>$orderblock_id,
                'parent_id'=>$parent_id]);
        } elseif($alias) {

            $urlRightDetail=Url::toRoute(['category/view-ajax','alias'=>$alias,'orderblock_id'=>$orderblock_id,'parent_id'=>$parent_id]);
        } else {
//            print_r(Yii::$app->request->queryString);
            if (array_key_exists ('ProductSearch',Yii::$app->request->queryParams)) {
                $urlRightDetail=Url::toRoute(['category/view-ajax','alias'=>$alias,'orderblock_id'=>$orderblock_id,'parent_id'=>$parent_id]);
                $urlRightDetail='';
                $htmRightDetail=$this->actionViewAjax(null,$alias,$orderblock_id,$parent_id);
//                $urlRightDetail=Url::toRoute(['category/view-ajax?'.Yii::$app->request->queryString,'alias'=>$alias,'orderblock_id'=>$orderblock_id,'parent_id'=>$parent_id]);
            } else {
                $urlRightDetail='';
            }
        }

        if (Yii::$app->request->isPjax) {
            if ($product) {
                $category=Category::findCategory($alias);
                return Yii::$app->runAction('product/update-ajax',['id'=>$product_id,'category'=>$category,'orderblock_id'=>$orderblock_id,'parent_id'=>$parent_id]);
            } elseif ($alias) {
                return $this->actionViewAjax(null,$alias,$orderblock_id,$parent_id);
            } else {
                return '';
            }
        } else {
            return $this->render('index', [
                'tree' => Category::findOne($root)->tree(),
                'urlRightDetail'=>$urlRightDetail,
                'activeNode'=>$active_id,
                'orderblock_id'=>$orderblock_id,
                'parent_id'=>$parent_id,
                'dateBegin'=>$dateBegin,
                'dateEnd'=>$dateEnd,
                'searchModel'=>$searchModel,
                'htmRightDetail'=>$htmRightDetail,
            ]);
        }
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
    public function actionViewAjax($category_id=null,$alias=null,$orderblock_id=null,$parent_id=null)
    {

        $model=Category::getRoot();
        $model=1;
        $session = Yii::$app->session;

        if ($category_id) {
            $model=$this->findModel($category_id);
        } elseif($alias) {
            $model=$this->findByAlias($alias);
        }

        if ((isset($_POST['hasEditable']))and isset($model)) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // read your posted model attributes
            if ($model->load($_POST)) {

                if ($model->validate()) {
                    if ($model->save()) {
                        $session->setFlash('success', 'Каталог успешно сохранен');
                        $value = $model->name;

                        $arrChildren = array();
                        $children = $model->children()->all();
                        foreach ($children as $child) {
                            $arrChildren[$child->id] = $child->alias;
                        }
                        return ['output' => $value, 'data' => ['url' => $model->getUrl(), 'children' => $arrChildren], 'message' => ''];

                    } else {
                        $session->setFlash('error', 'Ошибка при сохранении каталога');
                        return ['output' => '', 'data' => ['url' => $model->getUrl()], 'message' => $model->firstErrors];
                    }
                } else {
                    $session->setFlash('error', 'Ошибка при сохранении каталога');
                    return ['output' => '', 'data' => ['url' => $model->getUrl()], 'message' => $model->errors['name'][0]];
                }
            }
        } else {
            $searchModel = new ProductSearch();
            $productsDataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->renderAjax('view', [
                'model' => $model,
                'productsDataProvider' => $productsDataProvider,
                'orderblock_id'=>$orderblock_id,
                'parent_id'=>$parent_id
            ]);
        }

    }
    public function actionAddAjax($parent=0)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new Category();
        $session = Yii::$app->session;

        if ($parent==0) {
            $parent=$model->root;
        } else {
            $parent = Category::find()->andWhere(['id'=>$parent])->one();
        }

        $model->name=self::getNewName($parent);
        $model->client_id=User::findOne(Yii::$app->user->id)->client_id;

//        $model->prependTo($parent);
        $model->appendTo($parent);

        if ( $model->save()) {
//            $model->updateAlias();
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

//      Проверка на наличие товаров в категории
        if ($num=ProductCategory::find()->where(['category_id'=>$id])->count()) {
            $session->setFlash('error', 'Ошибка. В разделе "'.$model->name.'" '. $num.' товаров. Необходимо очистить раздел от товаров');
            return ['out' => $model->errors, 'status' => 'error'];
        }

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

            if ($model->save())
            {
//                $model->updateAlias();
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

    protected function findByAlias($alias)
    {
        if (($model = Category::find()->where(['alias'=>$alias])->one()) !== null) {
            return $model;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
//            return false;
        }

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
            $model=Category::findOne($item_model->id);
            $model->save();
            $data=$model->getUrl();
            $status="success";
        } else {
            $data=$item_model->errors;
            $status="error";
        }
        return ['data' => $data, 'status' => $status];
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

    /**
     * Функция возращает Новое имя раздела, что бы не было одинаковых
     * @param Category $category
     */
    private function getNewName($category)
    {
        return self::NEWNAME;
    }

    /**
     * изменения в разделе (название, параметры)
     * @param $category_id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateAjax($category_id)
    {
        $model = $this->findModel($category_id);
        $session = Yii::$app->session;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($post = Yii::$app->request->post()) {
            if ($model->load($post)) {
                $data = '';
                if ($model->save()) {
                    $status = true;
                    $session->setFlash('success', 'Измененния в разделе успешно сохраненны');
                } else {
                    $status = false;
                    $data = $model->getErrors('')[0];
                    $session->setFlash('error', $data);
                }
                return ['status' => $status, 'data' => $data];
            }
        }
    }

    public function actionUpload($id=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        return true;
        /** @var Category $category */
        $category=$this->findModel($id);
        if (empty($_FILES['files'])) {
            return ['error'=>'Нет файлов для загрузки'];
        }
        // get the files posted
        $files = $_FILES['files'];

        $hash = empty($_POST['hash']) ? $category->getHash() : $_POST['hash'];

        // a flag to see if everything is ok
        $success = null;

        // file paths to store
        $paths= [];

        // get file names
        $filenames = $files['name'];

        // loop and process files
        for($i=0; $i < count($filenames); $i++){
            $ext = explode('.', basename($filenames[$i]));
            $ext=array_pop($ext);

            $modelFile = new File();
            $modelFile->hash=$hash;
            $modelFile->ext=$ext;
            $modelFile->name=$filenames[$i];
            $modelFile->client_id=User::findOne(Yii::$app->user->id)->client_id;
            if (list($width, $height, $type, $attr) = getimagesize($files['tmp_name'][$i])) {
                $modelFile->width=$width;
                $modelFile->height=$height;
            }


            if ($modelFile->save()) {
                if(move_uploaded_file($files['tmp_name'][$i], $modelFile->getPath())) {
                    if  ($category->thumbnail_id) {
                        $tmpthumbnail=$category->thumbnail;
                        $category->thumbnail_id=null;
                    }

                    $category->thumbnail_id=$modelFile->id;
                    $category->save();
                    if ($tmpthumbnail) {
                        $tmpthumbnail->delete();
                    }
                    $success = true;
                } else {
                    $success = false;
                    $modelFile->delete();
                    break;
                }
            }

        }

        // check and process based on successful status
        if ($success === true) {
            $output = [];
        } elseif ($success === false) {
            $output = ['error'=>'Error while uploading images. Contact the system administrator'];
        } else {
            $output = ['error'=>'No files were processed.'];
        }
        // return a json encoded response for plugin to process successfully
        return $output;
    }
}
