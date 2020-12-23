<?php

namespace frontend\controllers\shop;

use frontend\widgets\Shop\CartWidget;
use rent\forms\Shop\AddToCartForm;
use rent\forms\Shop\Order\OrderForm;
use rent\readModels\Shop\ProductReadRepository;
use rent\useCases\Shop\CartService;
use rent\useCases\Shop\OrderService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CartController extends Controller
{
    public $layout = 'blank';

    private $products;
    private $service;
    private $orderService;

    public function __construct($id, $module, CartService $service, ProductReadRepository $products, OrderService $orderService,$config = [])
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->service = $service;
        $this->orderService = $orderService;
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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => ['add-ajax','addAjax','add'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new OrderForm();
        $cart = $this->service->getCart();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $order = $this->orderService->checkout(Yii::$app->user->id, $form);
//                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
                return $this->redirect(['/']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('index', [
            'cart' => $cart,
            'model' => $form,
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
    public function actionAddAjax($id,$type,$qty=1)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        try {
            $this->service->add($product->id, $qty,$type);

            Yii::$app->session->setFlash('success','Товар добавлен в заказ');
            return $this->asJson([
                'status' => 'success',
                'data' => [
                    ['id'=> 'mini-cart','html' => CartWidget::widget()],
                    ['id'=> 'icn_cart','html' => 22],
                ]]);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->asJson(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }
    public function actionAddAjaxPost($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $form = new AddToCartForm($product);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
//                var_dump($form);exit;
                $this->service->add($product->id, $form->qty,$form->type);
                Yii::$app->session->setFlash('success','Товар добавлен в заказ');
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