<?php

namespace backend\controllers\shop;

use rent\entities\Shop\Order\Order;
use rent\entities\User\User;
use rent\forms\manage\Shop\Order\OrderCreateForm;
use rent\forms\manage\Shop\Order\OrderEditForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\readModels\Shop\OrderReadRepository;
use rent\services\manage\Shop\OrderManageService;
use Yii;
use backend\forms\Shop\OrderSearch;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

    private $service;
    private $orders;

    public function __construct(
        $id,
        $module,
        OrderManageService $service,
        OrderReadRepository $orders,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->orders = $orders;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new OrderCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $order = $this->service->create($form);
            return $this->redirect(['update', 'id' => $order->id]);
        }

        return $this->render('create', [
            'model' => $form,

        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $order = $this->findModel($id);

        $form = new OrderEditForm($order);

        $payments_provider=$this->orders->getAllPayments($order);
        $payments_form = new PaymentForm($order);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($order->id, $form);
                return $this->redirect(['update', 'id' => $order->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'order' => $order,
            'payments_provider' => $payments_provider,
            'payments_form' => $payments_form
        ]);
    }

    public function actionPaymentAddAjax($id)
    {
        $order = $this->findModel($id);
        $form = new PaymentForm($order);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->addPayment($order->id, $form);
                return $this->asJson(['success' => true]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return ['status' => 'success', 'data' => ''];
                $result = [];
                // The code below comes from ActiveForm::validate(). We do not need to validate the model
                // again, as it was already validated by save(). Just collect the messages.
                foreach ($form->getErrors() as $attribute => $errors) {
                    $result[yii\helpers\Html::getInputId($form, $attribute)] = $errors;
                }
                return $this->asJson(['validation' => $result]);
            }
        }
    }

    public function actionPaymentDelete($id,$payment_id)
    {
        try {
            $this->service->removePayment($id, $payment_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['update', 'id' => $id,'#' => 'order-tab1']);
    }

    public function actionUpdateAjax($id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->post('hasEditable')) {
            $model = OrderProduct::findOne(Yii::$app->request->post('editableKey'));
            // fetch the first entry in posted data (there should only be one entry
            // anyway in this array for an editable submission)
            $posted = current($_POST['OrderProduct']);
            $post = ['OrderProduct' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output = '';
            }
            $out = ['output' => $output, 'message' => ''];
            return $out;
        }

        if (empty($id)) {
            $model = new Order();
        } else {
            $model = $this->findModel($id);
        }
        $session = Yii::$app->session;


        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $session->setFlash('success', 'Заказ сохранен');
                $session['activeOrderId'] = $model->id;
//                $data = $this->renderAjax('_orderHeaderBlock', ['orders' => Order::getActual()]);
                return ['out' => $model, 'status' => 'success', 'data' => ''];
            } else {
                $session->setFlash('error', 'Ошибка при сохранении заказа');
                return ['out' => 'Ошибка при сохранении заказа', 'status' => 'error'];
            }

        }
//        $data=$this->renderAjax('_modalForm',['order'=>$model]);
        $data = $this->renderAjax('_modalUpdateOrder', ['order' => $model]);
        return ['status' => 'success', 'data' => $data];
    }

//    выводим индекс в аякcе
    public function actionIndexAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $orders = Order::getActual();
        $session = Yii::$app->session;

        $post = Yii::$app->request->post();
        if ($activeOrderId = $post['activeId']) {
            $session['activeOrderId'] = $activeOrderId;
        }
//        $data = $this->renderAjax('_orderHeaderBlock', ['orders' => $orders]);
        return ['status' => 'success', 'data' => ''];

    }

    /**
     * Добавляем в заказ товар в аяксе
     * @return array
     */
    public function actionAddProductAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;

        $parent_id = null;

        $post = Yii::$app->request->post();

        if ((array_key_exists('orderblock_id', $post)) and ($post['orderblock_id'])) {
            $orderBlock_id = $post['orderblock_id'];
            $orderBlock = OrderBlock::findOne($orderBlock_id);
            $currentOrder = $orderBlock->order;
        } else {
            return false;
        }
//      Если создаем составную(пустую) позицию
        if (array_key_exists('parent_id', $post)) {
            $parent_id = $post['parent_id'];
            if ($parent_id == 'new') {
                if ($currentOrder->addEmptyToBasket($orderBlock_id)) {
                    $out = 'Коллекция успешно добавлена';
                    $session->setFlash('success', $out);
                    return ['status' => 'success', 'data' => $out];
                } else {
                    $out = 'Ошибка при добавлении коллекции';
                    $session->setFlash('error', $out);
                    return ['status' => 'error'];
                }
            }
        }
//      Если товар реален)
        if (($productId = $post['id']) and ($product = \common\models\Product::findOne($productId))) {
            $qty = empty($post['qty']) ? 1 : $post['qty'];

//          Определяем какой товар. Аренда продажа
            if (array_key_exists('pricerent', $post)) {
                $type = OrderProduct::RENT;
            } elseif (array_key_exists('pricesale', $post)) {
                $type = OrderProduct::SALE;
            } else {
                $session->setFlash('error', 'Ошибка при добавлении товара в заказ. У товара не указана цена. ');
                return ['status' => 'error', 'data' => 'Ошибка при добавлении товара в заказ. У товара не указана цена. '];
            }
            if (($result = $currentOrder->addToBasket($productId, $qty, $type, $orderBlock_id, $parent_id)) === true) {
                $out = 'Товар добавлен в заказ';
                $data = $out;
//                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
            } else {
                $out = "Ошибка при добавлени товара в заказ: " . $result;
            }
        }

        if (empty($data)) {
            $session->setFlash('error', $out);
            return ['status' => 'error'];
        } else {
            $session->setFlash('success', $out);
            return ['status' => 'success', 'data' => $data];
        }

    }



    /**
     * Deletes an existing Order model.
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
     * Удаление позиции в заказе
     * @param $orderProduct_id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteOrderProduct($orderProduct_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;

        if ($model = OrderProduct::findOne($orderProduct_id)) {
            if ($model->delete()) {
                $out = 'Позиция заказа удалена';
                $session->setFlash('success', $out);
                return ['status' => 'success', 'data' => $out];
            } else {
                $out = 'Ошибка при удалении позиции заказа';
            }
        } else {
            $out = 'Ошибка. Не найдена позиция для удаления';
        }
        $session->setFlash('error', $out);
        return ['status' => 'error', 'data' => $out];
    }

    /**
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Order
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * контроллер возращает содеражание модальноо окна для подверждения операции движения с товарами в заказе
     */
    public function actionContentConfirmModalAjax($order_id)
    {
//      TODO: а можно ли данном пользователю так делать
        if ($order = Order::findOne($order_id)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ((isset($_POST['keylist'])) and (isset($_POST['operation']))) {
                $keys = $_POST['keylist'];

                //            $query = OrderProduct::find()->where(['<>','type','collect']);
                $query = OrderProduct::find()->where(['order_id' => $order_id])->andWhere(['<>', 'type', 'service']);
                if (is_array($keys)) {
                    //Возращаем все позиции
                    $query = $query->andWhere(['in', 'parent_id', $keys]);
                }
                //Не надо возращать позиции которые продали или составные:)
                if ($_POST['operation'] == Action::RETURN) {
                    $query = $query->andWhere(['<>', 'type', 'sale']);
                    $query = $query->andWhere(['<>', 'type', 'collect']);
                }
                $dataProvider = new ActiveDataProvider([
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                    'query' => $query,
                ]);
                $dataProvider->pagination = false;
                $out = $this->renderAjax('_modalConfirmOperation', [
                    'dataProvider' => $dataProvider,
                    'operation' => $_POST['operation']
                ]);
                $statusResponse = 'success';
            } else {
                $statusResponse = 'error';
                $out = 'Ошибка при передаче данных серверу';
            }
        } else {
            $statusResponse = 'error';
            $out = "Не найден заказ";
        }
        $session = Yii::$app->session;
        $session->setFlash($statusResponse, $out);
        return ['status' => $statusResponse, 'data' => $out];
    }

    /**
     * Добавление блока в заказ
     */
    public function actionAddBlockAjax($order_id, $block_name = null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $order = Order::findOne($order_id);
        if ($block_name) {
            $orderBlock = new OrderBlock(['name' => $block_name, 'order_id' => $order->id]);
            if (!($orderBlock->save())) {
                return ['status' => 'error', 'data' => $orderBlock->firstErrors];
            }
        }
        $orderBlocks = $order->getOrderProductsByBlock($orderBlock->id);

        $data = $this->renderAjax('_orderBlock', [
            'block' => reset($orderBlocks)
        ]);
        return ['status' => 'success', 'html' => $data];

    }

    /**
     * Удаление блока из заказа
     */
    public function actionDeleteBlockAjax($orderblock_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;
        if ($model = OrderBlock::findOne($orderblock_id)) {
            // Проверка на наличие позиций в блоке
            if ($model->getOrderProducts()->count()) {
                $out = 'Нельзя удалить блок с товарами';
                $status = 'error';
            } else {
                if ($model->delete()) {
                    $out = 'Блок удален';
                    $status = 'success';
                } else {
                    $out = 'Ошибка при удалении блока';
                    $status = 'error';
                }
            }
        } else {
            $out = 'Ошибка. Не найден блок для удаления';
            $status = 'error';
        }

        $session->setFlash($status, $out);
        return ['status' => $status, 'data' => $out];
    }

    public function actionAddCashModalAjax($order_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new Cash();
        $cashTypes = CashType::find()->all();

        $data = $this->renderAjax('_modalAddCash', [
            'model' => $model,
            'order_id' => $order_id,
            'cashTypes' => $cashTypes
        ]);
        return ['status' => 'success', 'data' => $data];
    }

    /**
     * Экспорт заказа в файл, Если заказ не указан, тогда выгражаем все заказы
     * @param $order_id
     */
    public function actionExport($order_id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($order_id) {
            if ($url = self::exportOrderToExcel($order_id)) {
                return ['status' => 'success', 'data' => $url];
            } else {
                return ['status' => 'error', 'data' => ""];
            }
        } else {
            if ($url = self::exportOrdersToExcel()) {
                return ['status' => 'success', 'data' => $url];
            } else {
                return ['status' => 'error', 'data' => ""];
            }
        }
    }

    private function getDir()
    {
        $exportDir = \Yii::$app->params['exportDir'];
        if (!(is_dir(Yii::getAlias('@backend/web' . DIRECTORY_SEPARATOR . $exportDir))))
            mkdir(Yii::getAlias('@backend/web' . DIRECTORY_SEPARATOR . $exportDir), 0755, true);

        return $exportDir;
    }

    private function getPath($fileName)
    {
        return Yii::getAlias('@backend/web' . DIRECTORY_SEPARATOR . self::getDir()) . $fileName;
    }

    private function getUrl($fileName)
    {
        return Yii::$app->request->baseUrl . self::getDir() . $fileName;
    }

    /**
     * Формируем выгрузку заказа в Excel
     * @param $order_id
     * @return string
     * @throws NotFoundHttpException
     * @throws PhpSpreadsheet\Exception
     * @throws PhpSpreadsheet\Writer\Exception
     */
    private function exportOrderToExcel($order_id)
    {
        $order = self::findModel($order_id);
        $orderBlocks = $order->getOrderProductsByBlock();
        $dateBegin = date_create($order->dateBegin);
        $dateBegin = date_format($dateBegin, 'Y-m-d');
        $fileName = $dateBegin . '_' . $order->name . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $currentRow = 1;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($dateBegin);

//        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('A')->setWidth(13.71);
        $sheet->getColumnDimension('B')->setWidth(32.71);
        $sheet->getColumnDimension('C')->setWidth(9.43);
        $sheet->getColumnDimension('D')->setWidth(8);
        $sheet->getColumnDimension('E')->setWidth(8);
        $sheet->getColumnDimension('F')->setWidth(10.86);
//      стили
        $styleMain = array(
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ),
        );
        $styleHeaderOrder = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
//                    'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
//                'wrapText' => true,
            ),
            'borders' => array(
                'top' => array(
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
                'bottom' => array(
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
                'right' => array(
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
                'left' => array(
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        //шапка таблицы
        $styleHeaderTable =
            array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '000000'),
                    ),
                ),
                'fill' => array(
                    'fillType' => PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('argb' => 'c0c0c0'),
                ),
            );
        //подпись
        $styleSignature = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ),
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $styleSignature2 = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ),
            'font' => array(
                'size' => 8,
            ),

        );

        $begin = $currentRow;
        $sheet->setCellValue('A' . $currentRow, 'руководитель');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'фотограф');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'флорист');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'организатор');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'регистратор');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'источник');
        $sheet->getStyle('A' . $begin . ':F' . $currentRow)->applyFromArray($styleHeaderOrder);

        $currentRow++;
        $currentRow++;
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);
        $sheet->getStyle('B' . $begin . ':F' . $currentRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ),
        ));
        $sheet->setCellValue('B' . $currentRow, 'Приложение к договору №____ от   ___.___.______');

        $currentRow++;
        $currentRow++;

        $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
        $sheet->getStyle('B' . $currentRow . ':B' . $currentRow)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ),
            )
        );
        $sheet->setCellValue('B' . $currentRow, 'Бланк заказа');

        $currentRow++;
        $currentRow++;

        $begin = $currentRow;
        $sheet->setCellValue('B' . $currentRow, 'дата');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $dateBegin = date_create($order->dateBegin);
        $sheet->setCellValue('C' . $currentRow, date_format($dateBegin, 'd.m.Y'));
        $currentRow++;
        $sheet->setCellValue('B' . $currentRow, 'молодожены');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C' . $currentRow, $order->customer);
        $currentRow++;
        $sheet->setCellValue('B' . $currentRow, 'контакты');
        $currentRow++;
        $sheet->setCellValue('B' . $currentRow, 'место');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C' . $currentRow, $order->address);
        $sheet->getStyle('A' . $begin . ':F' . $currentRow)->applyFromArray($styleHeaderOrder);

        $currentRow++;
        $sheet->setCellValue('H' . $currentRow, 'примечание');


        $mainItog = 0;

        foreach ($orderBlocks as $orderBlock) {
            $currentRow++;
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleHeaderTable);
            $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, $orderBlock['orderBlock']->name);
            $sheet->setCellValue('C' . $currentRow, 'цена');
            $sheet->setCellValue('D' . $currentRow, 'кол-во');
            $sheet->setCellValue('E' . $currentRow, 'период');
            $sheet->setCellValue('F' . $currentRow, 'сумма');

            $itog = 0;

            $currentRow++;
            $begin = $currentRow;
            /** @var  $dataProvider  ArrayDataProvider */
            $dataProvider = $orderBlock['dataProvider'];
            $orderProducts = $dataProvider->allModels;
            foreach ($orderProducts as $orderProduct) {

                if ($orderProduct['type'] == \common\models\OrderProduct::COLLECT) {
                    $name = $orderProduct['name'];
                } else {
                    $model = \common\models\Product::findOne($orderProduct['product_id']);
                    $name = $model->name;
                }
                $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
                $sheet->setCellValue('A' . $currentRow, $name);
                $sheet->setCellValue('C' . $currentRow, $orderProduct['cost']);
                $sheet->setCellValue('D' . $currentRow, $orderProduct['qty']);
                $summ = $orderProduct['cost'] * $orderProduct['qty'];
                if ($orderProduct['type'] == \common\models\OrderProduct::RENT) {
                    $summ = $summ * $orderProduct['period'];
                    $sheet->setCellValue('E' . $currentRow, $orderProduct['period']);
                }
                if ($orderProduct['comment']) {
                    $sheet->setCellValue('H' . $currentRow, $orderProduct['comment']);
                }
                $itog += $summ;
                $sheet->setCellValue('F' . $currentRow, $summ);
                $currentRow++;
            }


            $sheet->getStyle('A' . $begin . ':F' . ($currentRow - 1))->applyFromArray(array(
                'font' => array(
                    'bold' => false,
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '000000'),
                    ),
                ),
            ));
            $sheet->setCellValue('E' . $currentRow, 'Итого:');
            $sheet->setCellValue('F' . $currentRow, $itog);
            $mainItog += $itog;
            $currentRow++;
        }

        $currentRow++;
        $styleItogo = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ),
        );
//      Итого по декору
        $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
        $sheet->getStyle('B' . $currentRow . ':E' . $currentRow)->applyFromArray($styleItogo);
        $sheet->setCellValue('B' . $currentRow, 'Итого по декору');
        $sheet->setCellValue('F' . $currentRow, $mainItog);
        $currentRow++;
//      Итого по услугам
        foreach ($order->getServices() as $service) {
            $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
            $sheet->getStyle('B' . $currentRow . ':E' . $currentRow)->applyFromArray($styleItogo);
            $sheet->setCellValue('B' . $currentRow, $service['name']);
            $sheet->setCellValue('F' . $currentRow, $service['cost']);
            $mainItog += $service['cost'];
            $currentRow++;
        }
//      Общее Итого
        $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
        $sheet->getStyle('B' . $currentRow . ':E' . $currentRow)->applyFromArray($styleItogo);
        $sheet->setCellValue('B' . $currentRow, 'Общая стоимость товаров и услуг');
        $sheet->setCellValue('F' . $currentRow, $mainItog);
        $currentRow++;

        $currentRow++;
        $sheet->mergeCells('A' . $currentRow . ':F' . ($currentRow + 1));
        $sheet->getStyle('A' . $currentRow . ':C' . $currentRow)->applyFromArray($styleMain);
        $sheet->setCellValue('A' . $currentRow, 'В стоимость проката конструкций, тканей и элементов декора не включены услуги по монтажу и демонтажу украшений.');
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
        $sheet->getStyle('A' . $currentRow . ':C' . $currentRow)->applyFromArray($styleMain);
        $sheet->setCellValue('A' . $currentRow, 'С состоимостью согласен, количество предметов проката указано верно');

        $sheet->getStyle('D' . $currentRow . ':F' . $currentRow)->applyFromArray($styleSignature);
        $currentRow++;
        $sheet->getStyle('D' . $currentRow . ':F' . $currentRow)->applyFromArray($styleSignature2);
        $sheet->setCellValue('D' . $currentRow, 'дата');
        $sheet->setCellValue('E' . $currentRow, 'подпись');
        $sheet->setCellValue('F' . $currentRow, 'расшифровка');

        $writer = new Xlsx($spreadsheet);

//        TODO: Написать бы исключения
        $writer->save(self::getPath($fileName));
        return self::getUrl($fileName);
        return 'https://ya.ru/';
    }


    private function exportOrdersToExcel()
    {
        $fileName = 'orders_' . date("d-m-y_h-i-s") . '.xlsx';

        $searchModel = new OrderSearch();
        $params = Yii::$app->request->queryParams;
        if (count($params) < 1) {
            $params = Yii::$app->session['orderparams'];
            if (isset(Yii::$app->session['orderparams']['page']))
                $_GET['page'] = Yii::$app->session['orderparams']['page'];
        } else {
            Yii::$app->session['orderparams'] = $params;
        }

        $dataProvider = $searchModel->search($params);
        $rows=$dataProvider->query->all();

        $spreadsheet = new Spreadsheet();
        $currentRow = 1;
        $sheet = $spreadsheet->getActiveSheet();




//      Определяем ширину
//        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(8);
        $sheet->getColumnDimension('E')->setWidth(8);
        $sheet->getColumnDimension('F')->setWidth(10.86);

//      Назначаем стили
        // Блок заказа
        $styleOrderBlock = array(
            'font' => array(
                'bold' => false,
            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            )
        );
        //шапка таблицы
        $styleHeaderTable =
            array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '000000'),
                    ),
                ),
                'fill' => array(
                    'fillType' => PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('argb' => 'c0c0c0'),
                ),
            );

        //      Шапка
        $sheet->setCellValue('A' . $currentRow, 'id');
        $sheet->setCellValue('B' . $currentRow, 'Название заказа|блока|позиции');
        $sheet->setCellValue('C' . $currentRow, 'Дата заказа(Цена)');
        $sheet->setCellValue('D' . $currentRow, 'Кол-во');
        $sheet->setCellValue('E' . $currentRow, 'Период');
        $sheet->setCellValue('F' . $currentRow, 'Сумма');
        $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleHeaderTable);

        /** @var Order $row */
        foreach ($rows as $row) {
            $currentRow++;
            $sheet->setCellValue('A' . $currentRow, $row->id);
            $sheet->setCellValue('B' . $currentRow, $row->name);
            $dateBegin = date_create($row->dateBegin);
            $sheet->setCellValue('C' . $currentRow, date_format($dateBegin, 'd.m.Y'));
            $sheet->setCellValue('F' . $currentRow, $row->getSumm());
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleHeaderTable);
            /** @var OrderBlock $orderBlock */
            foreach ($row->orderBlocks as $orderBlock) {
                $currentRow++;
                $sheet->mergeCells('A' . $currentRow . ':F' . ($currentRow));
                $sheet->setCellValue('A' . $currentRow, $orderBlock->name);
                $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleOrderBlock);
                foreach ($orderBlock->orderProducts as $orderProduct) {
                    //Пропускаем товары которые в составных
                    if ($orderProduct->id!=$orderProduct->parent_id) {
                        continue;
                    }
                    if ($orderProduct->product_id) {
                        $name=$orderProduct->product->name;
                    } else {
                        $name = $orderProduct->name;
                    }
                    $currentRow++;
                    $sheet->mergeCells('A' . $currentRow . ':B' . ($currentRow));
                    $sheet->setCellValue('A' . $currentRow, $name);
                    $sheet->setCellValue('C' . $currentRow, $orderProduct->cost);
                    $sheet->setCellValue('D' . $currentRow, $orderProduct->qty);
                    $sheet->setCellValue('E' . $currentRow, $orderProduct->period);
                    $sheet->setCellValue('F' . $currentRow, $orderProduct->getSumm());
//                    $sheet->setCellValue('E' . $currentRow, $orderProduct->name);
//                    $sheet->setCellValue('F' . $currentRow, $orderProduct->name);
//                    $sheet->setCellValue('A' . $currentRow, $orderProduct->name);
                }
            }
            // Выводим услуги если есть
            if ($services=$row->getServices()) {
                $currentRow++;
                $sheet->mergeCells('A' . $currentRow . ':F' . ($currentRow));
                $sheet->setCellValue('A' . $currentRow, "УСЛУГИ");
                $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleOrderBlock);
                /** @var OrderProduct $service */
                foreach ($services as $service) {
//                    if ($service->product_id) {
//                        $name=$service->service->name;
//                    } else {
//                        $name = $service->name;
//                    }
                    $currentRow++;
                    $sheet->mergeCells('A' . $currentRow . ':B' . ($currentRow));
                    $sheet->setCellValue('A' . $currentRow, $service['name']);
                    $sheet->setCellValue('C' . $currentRow, $service['cost']);
//                    $sheet->setCellValue('D' . $currentRow, $service['qty']);
                    $sheet->setCellValue('F' . $currentRow, $service['cost']);
                }
            }

        }
        $writer = new Xlsx($spreadsheet);
//        TODO: Написать бы исключения
        $writer->save(self::getPath($fileName));
        return self::getUrl($fileName);


    }

    /**
     * Добавление Услуги в заказ
     */
    public function actionAddServiceAjax($order_id,$service_id)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $order=Order::findOne($order_id);
        $dataProviderService=new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $order->getServicesQuery(),
        ]);
        if ($order->addServiceToBasket($service_id)) {
            $data= $this->renderAjax('_services',[
                'services'=>$order->getServices(),
                'dataProviderService'=>$dataProviderService,
            ]);
            return ['status' => 'success','html'=>$data];
        } else {
            return ['status' => 'error','html'=>''];
        }

    }

    /**
     * Изменение статуса заказа. Возможно только изменитьна Закрыт или Отменен
     * @param $order_id
     * @param $status_id
     * @return array
     */
    public function actionUpdateStatusAjax($order_id, $status_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;
        $statusResponse='';
        $out='';
        if (($status_id==Status::CLOSE) or ($status_id==Status::CANCELORDER)) {
//            TODO: проверка на то кто может статусы менять
            if ($order=Order::findOne($order_id)) {
                if ($order->changeStatus($status_id)) {
                    $out='Статус заказа изменен';
                    $statusResponse='success';
                } else {
                    $out='Ошибка при изменении статуса заказа';
                    $statusResponse='error';
                }
            } else {
                $out='Не найден заказа с id: '.$order_id.' Обратитесь к администратору';
                $statusResponse='error';
            }
        }else {
            $out='Нельзя сменить на статус: '.$status_id.' Обратитесь к администратору';
            $statusResponse='error';
        }
        if ((!empty($statusResponse)) and(!empty($out))) {
            $session->setFlash($statusResponse, $out);
            return ['status' => $statusResponse,'data'=>$out];
        }

    }

    public function actionChangeStatus($id)
    {

        if ($order=Order::findOne($id)) {
            return $order->changeStatus();
        } else {
            return false;
        }
    }

    public function actionClearMovement($id,$deactive=1)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;
        $statusResponse='success';
        $out='Успешно завершилась '.(($deactive==1)?'деактивация движений':'удаление движений');
        if ($order=Order::findOne($id)) {
            foreach ($order->orderProducts as $orderProduct) {
                if ($deactive) {
                    if ($orderProduct->deactivateMovement()===false) {
                        $out='Ошибка при деактивации движений позиции: '.$orderProduct->name.' Обратитесь к администратору';
//                        $out.='<br>'.$orderProduct->errors[0];
                        $statusResponse='error';
                        break;
                    }
                } else {
                    if ($orderProduct->removeMovement()===false) {
                        $out='Ошибка при удалении движений позиции: '.$orderProduct->name.' Обратитесь к администратору';
                        $statusResponse='error';
                        break;
                    }
                }

            }
        } else {
            $out='Ошибка не найден заказ с id: '.$id.' Обратитесь к администратору';
            $statusResponse='error';
        }
        $session->setFlash($statusResponse, $out);
        return ['status' => $statusResponse,'data'=>$out];
    }



}
