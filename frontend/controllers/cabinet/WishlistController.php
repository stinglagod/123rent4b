<?php

namespace frontend\controllers\cabinet;

use frontend\widgets\Shop\CartWidget;
use rent\readModels\Shop\ProductReadRepository;
use rent\useCases\cabinet\WishlistService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class WishlistController extends Controller
{
    public $layout = 'cabinet';
    private $service;
    private $products;

    public function __construct($id, $module, WishlistService $service, ProductReadRepository $products, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->products = $products;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['POST'],
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
        $dataProvider = $this->products->getWishList(\Yii::$app->user->id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionAdd($id)
    {
        try {
            $this->service->add(Yii::$app->user->id, $id);
            Yii::$app->session->setFlash('success', 'Success!');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }
    public function actionAddAjax($id)
    {
        try {
            $this->service->add(Yii::$app->user->id, $id);
            Yii::$app->session->setFlash('success', 'Success!');
            return $this->asJson([
                'status' => 'success',
                'data' => [
                    ['id'=> 'icn_wishlist','html' => Yii::$app->settings->user->getAmountWishListItems()],
                ]
            ]);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->asJson(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove(Yii::$app->user->id, $id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }
}