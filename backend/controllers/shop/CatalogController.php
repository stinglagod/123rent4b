<?php

namespace backend\controllers\shop;


use rent\cart\Cart;
use rent\cart\CartItem;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Product\Product;
use rent\forms\manage\Shop\CategoryForm;
use rent\forms\manage\Shop\Order\OrderCartForm;
use rent\forms\manage\Shop\Product\MovementForm;
use rent\forms\manage\Shop\Product\PhotosForm;
use rent\forms\manage\Shop\Product\ProductCreateForm;
use rent\forms\manage\Shop\Product\ProductEditForm;
use rent\forms\Shop\Search\SearchForm;
use rent\useCases\manage\Shop\CategoryManageService;
use rent\useCases\manage\Shop\OrderManageService;
use rent\useCases\manage\Shop\ProductManageService;
use Yii;
use rent\entities\Shop\Category\Category;
use backend\forms\Shop\CategorySearch;
use yii\filters\AccessControl;
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
    private $serviceProduct;
    private $serviceOrder;


    public function __construct(
        $id,
        $module,
        CategoryManageService $service,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        ProductManageService $serviceProduct,
        \rent\useCases\manage\Shop\OrderManageService $serviceOrder,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->products = $products;
        $this->categories = $categories;
        $this->serviceProduct=$serviceProduct;
        $this->serviceOrder=$serviceOrder;
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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex($layout=null)
    {
        $searchModel = new CategorySearch();

        if (!$root=Category::getRoot()) {
            throw new NotFoundHttpException('The requested site does not exist.');
        }

        if ($layout) $this->setLayout('order');

        $searchForm = new SearchForm();
        $tree=$root->tree('root');

        if (($searchForm->load(\Yii::$app->request->queryParams))and ($searchForm->validate())) {
            $dataProvider = $this->products->search($searchForm);
            if ($searchForm->site) {
                \Yii::$app->settings->initSite(intval($searchForm->site));
                \Yii::$app->settings->save();
            }
        } else {
            $dataProvider = null;
        }
        $order=null;
        return $this->render('index', [
            'tree'=> $tree,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchForm,
            'order' =>$order
        ]);
    }

    public function actionOrderIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!$root=Category::getRoot()) {
            throw new NotFoundHttpException('The requested site does not exist.');
        }

        $this->setLayout('order');

        $searchForm = new SearchForm();

        $tree=$root->tree('root');

        if (($searchForm->load(\Yii::$app->request->queryParams))and ($searchForm->validate())) {
            $dataProvider = $this->products->search($searchForm);
        } else {
            $dataProvider = null;
        }


        Yii::$app->view->params['orderCartForm'] = new OrderCartForm();

        $order=$this->findOrder(Yii::$app->view->params['orderCartForm']->order_id);

        return $this->render('index', [
            'tree'=> $tree,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchForm,
            'order' =>$order
        ]);
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCategory($id,$layout=null)
    {
        if (!$category = $this->categories->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $searchForm = new SearchForm();


        if (($searchForm->load(\Yii::$app->request->queryParams))and ($searchForm->validate())) {
            $dataProvider = $this->products->search($searchForm);
            $tree= Category::getRoot()->tree();

        } else {
            $dataProvider = $this->products->getAllByCategory($category);
            $tree=Category::getRoot()->tree($category->slug);
        }



        $order=null;
        if ($layout) {
            $this->setLayout($layout);
            //TODO: это тут не надо. сделал только для получения order_id
            Yii::$app->view->params['orderCartForm'] = new OrderCartForm();
            $order=$this->findOrder(Yii::$app->view->params['orderCartForm']->order_id);
        }

        return $this->render('category', [
            'tree'=> $tree,
            'category' => $category,
            'searchModel' => $searchForm,
            'dataProvider' => $dataProvider,
            'order' =>$order
        ]);
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

    public function actionOnSite($product_id,$on)
    {
        try {
            $product=$this->findProduct($product_id);
            $this->serviceProduct->onSite($product->id,$on);
            return $this->asJson(['status' => 'success', 'data' =>$on]);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->asJson(['status' => 'error']);
        }

    }

################################################
    /**
     * @param integer $id
     * @return \rent\entities\Shop\Category\Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Category
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProduct($id): Product
    {
        if (($model = Product::findOne(['id'=>$id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist. Not find product');
    }
    protected function findOrder($id): Order
    {
        if (($model = Order::findOne(['id'=>$id])) !== null) {
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
###Product

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionProduct($id,$layout=null)
    {
        $product = $this->findProduct($id);

        $this->setLayout($layout);

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->serviceProduct->addPhotos($product->id, $photosForm);
                return $this->redirect([  $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('product/view', [
            'product' => $product,
//            'model'=>$form,
            'photosForm' =>$photosForm
        ]);
    }
    /**
     * @return mixed
     */
    public function actionProductCreate($category_id=null)
    {
        $form = new ProductCreateForm();
        $category=null;
        if ($category_id) {
            $category=$this->findModel($category_id);
            $form->categories->main=$category;
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $product = $this->serviceProduct->create($form);
                return $this->redirect([$product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
//        dump($form->load(Yii::$app->request->post()));exit;
        return $this->render('product/create', [
            'model' => $form,
            'category' =>$category
        ]);
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionProductUpdate($id)
    {
        $product = $this->findProduct($id);

        $form = new ProductEditForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->serviceProduct->edit($product->id, $form);
                return $this->redirect([$product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('product/update', [
            'model' => $form,
            'product' => $product,
        ]);
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionProductDelete($id)
    {
        try {
            $this->serviceProduct->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }
    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionDeletePhoto($id, $photo_id)
    {
        try {
            $this->serviceProduct->removePhoto($id, $photo_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect([  $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionMovePhotoUp($id, $photo_id)
    {
        $this->serviceProduct->movePhotoUp($id, $photo_id);
        return $this->redirect([  $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionMovePhotoDown($id, $photo_id)
    {
        $this->serviceProduct->movePhotoDown($id, $photo_id);
        return $this->redirect([  $id, '#' => 'photos']);
    }
    /**
 * @param $id
 * @return mixed
 * @throws NotFoundHttpException
 */
    public function actionProductMovement($id)
    {
        $product = $this->findProduct($id);
        $dataProvider = $this->products->getBalance($id);

        return $this->render('product/movement', [
            'dataProvider'=>$dataProvider,
            'product' => $product,
        ]);
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionProductMovementAdd($id)
    {
        $product = $this->findProduct($id);
        $movementsForm = new MovementForm($product);

        if ($movementsForm->load(Yii::$app->request->post()) && $movementsForm->validate()) {
            try {
                $this->serviceProduct->addMovement($product->id, $movementsForm);
                return $this->redirect(['product-movement','id'=>$product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('product/movement-add', [
            'product' => $product,
            'model' => $movementsForm
        ]);
    }
    public function actionProductMovementDelete($id,$movement_id)
    {
        $product = $this->findProduct($id);
        try {
            $this->serviceProduct->removeMovement($product->id, $movement_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['product-movement','id'=>$product->id]);
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionProductActivate($id)
    {
        try {
            $this->serviceProduct->activate($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect([$id]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionProductDraft($id)
    {
        try {
            $this->serviceProduct->draft($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect([$id]);
    }

    public function actionCategory404($layout)
    {
        $this->setLayout($layout);
        throw new NotFoundHttpException('The requested page does not exist. Don not find category.');
    }



##################################################
    private function setLayout($param=null)
    {
        if ($param==null) return;


        Yii::$app->layout = $param.'/'.Yii::$app->layout;
    }

}
