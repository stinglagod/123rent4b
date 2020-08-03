<?php

namespace backend\controllers\shop;


use http\Exception\RuntimeException;
use paulzi\nestedsets\NestedSetsBehavior;
use rent\forms\manage\Shop\CategoryForm;
use rent\services\manage\Shop\CategoryManageService;
use Yii;
use rent\entities\Shop\Category;
use backend\forms\Shop\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rent\readModels\Shop\CategoryReadRepository;
use rent\readModels\Shop\ProductReadRepository;

class CatalogController extends Controller
{
    private $service;
    private $categories;
    private $products;


    public function __construct(
        $id,
        $module,
        CategoryManageService $service,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->products = $products;
        $this->categories = $categories;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $tree=Category::getRoot()->tree('root');

        return $this->render('index', [
            'tree'=> $tree,

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCategory($id)
    {
        if (!$category = $this->categories->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->products->getAllByCategory($category);

        $tree=Category::getRoot()->tree($category->slug);

        return $this->render('category', [
            'tree'=> $tree,
            'category' => $category,

//            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
//        return $this->render('category', [
//            'category' => $category,
//            'dataProvider' => $dataProvider,
//        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'category' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate($id=null)
    {
        $form = new CategoryForm();
        if ($id) {
            $form->parentId=$id;
        }
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $category = $this->service->create($form);
                return $this->redirect(['category', 'id' => $category->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $category = $this->findModel($id);
        $form = new CategoryForm($category);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($category->id, $form);
                return $this->redirect(['category', 'id' => $category->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'category' => $category,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionMoveUp($id)
    {
        $this->service->moveUp($id);
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionMoveDown($id)
    {
        $this->service->moveDown($id);
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Category
    {
        if (($model = Category::findOne(['id'=>$id,'site_id'=>Yii::$app->params['siteId']])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionMove($item, $action, $second)
    {
        $status='success';
        $data='';
        try {
            $this->service->move($item,$second,$action);
            Yii::$app->session->setFlash('success', 'Категория успешно перемещена');
        } catch (\DomainException $e) {
            $status="error";
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        return ['data' => $data, 'status' => $status];
    }
}
