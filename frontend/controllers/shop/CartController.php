<?php

namespace frontend\controllers\shop;

use rent\forms\Shop\AddToCartForm;
use rent\readModels\Shop\ProductReadRepository;
use rent\useCases\Shop\CartService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CartController extends Controller
{
    public $layout = 'blank';

    private $products;
    private $service;

    public function __construct($id, $module, CartService $service, ProductReadRepository $products, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'quantity' => ['POST'],
                    'remove' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $cart = $this->service->getCart();

        return $this->render('index', [
            'cart' => $cart,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->layout = 'blank';

        $form = new AddToCartForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->add($product->id, $form->qty,$form->type);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('add', [
            'product' => $product,
            'model' => $form,
        ]);
    }
    public function actionAddAjax($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $form = new AddToCartForm($product);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
//                var_dump($form);exit;
                $this->service->add($product->id, $form->qty,$form->type);
                return $this->asJson(['status' => 'success', 'data' => '']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->asJson(['status' => 'error', 'data' => $e->getMessage()]);
            }
        } else {
            Yii::$app->session->setFlash('error','Не верный запрос');
            return $this->asJson(['status' => 'error', 'data' => 'Не верный запрос']);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionQuantity($id)
    {
        try {
            $this->service->set($id, (int)Yii::$app->request->post('quantity'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }
}