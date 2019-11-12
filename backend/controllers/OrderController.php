<?php

namespace backend\controllers;

use common\models\Action;
use common\models\Block;
use common\models\Cash;
use common\models\CashType;
use common\models\Movement;
use common\models\OrderBlock;
use common\models\OrderCash;
use common\models\OrderProduct;
use common\models\OrderProductAction;
use common\models\Status;
use common\models\User;
use Yii;
use common\models\Order;
use backend\models\OrderSearch;
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
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

//    public function actionCreateAjax()
//    {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $model = new Order();
//        $session = Yii::$app->session;
//
//        if ($model->load(Yii::$app->request->post())) {
//            if ($model->save()) {
//                $session->setFlash('success', 'Новый заказ создан');
//                $session['activeOrderId'] = $model->id;
//                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
//                return ['out' => $model, 'status' => 'success','data'=>$data];
//            } else {
//                $session->setFlash('error', 'Ошибка при создании нового заказа');
//                return ['out' => 'Ошибка при создании нового заказа', 'status' => 'error'];
//            }
//
//        }
//        $data=$this->renderAjax('_modalForm',['order'=>$model]);
//        return ['status' => 'success','data'=>$data];
//    }

    public function actionUpdateAjax($id=null)
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
                $output='';
            }
            $out = ['output'=>$output, 'message'=>''];
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
                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
                return ['out' => $model, 'status' => 'success','data'=>$data];
            } else {
                $session->setFlash('error', 'Ошибка при сохранении заказа');
                return ['out' => 'Ошибка при сохранении заказа', 'status' => 'error'];
            }

        }
//        $data=$this->renderAjax('_modalForm',['order'=>$model]);
        $data=$this->renderAjax('_modalUpdateOrder',['order'=>$model]);
        return ['status' => 'success','data'=>$data];
    }
//    выводим индекс в аякcе
    public function actionIndexAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orders=Order::getActual();
        $session = Yii::$app->session;

        $post=Yii::$app->request->post();
        if ($activeOrderId=$post['activeId']) {
            $session['activeOrderId'] = $activeOrderId;
        }
        $data=$this->renderAjax('_orderHeaderBlock',['orders'=>$orders]);
        return ['status' => 'success','data'=>$data];

    }
    //    Добавляем в заказ товар в аяксе
    public function actionAddProductAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $session = Yii::$app->session;

        $parent_id=null;

        $post=Yii::$app->request->post();

        if ((array_key_exists('orderblock_id',$post))and($post['orderblock_id'])) {
            $orderBlock_id=$post['orderblock_id'];
            $orderBlock=OrderBlock::findOne($orderBlock_id);
            $currentOrder=$orderBlock->order;
        } else {
            return false;
        }
//      Если создаем составную(пустую) позицию
        if (array_key_exists('parent_id',$post)){
            $parent_id=$post['parent_id'];
            if ($parent_id=='new'){
                if ($currentOrder->addEmptyToBasket($orderBlock_id)) {
                    $out='Коллекция успешно добавлена';
                    $session->setFlash('success', $out);
                    return ['status' => 'success','data'=>$out];
                } else {
                    $out='Ошибка при добавлении коллекции';
                    $session->setFlash('error', $out);
                    return ['status' => 'error'];
                }
            }
        }
//      Если товар реален)
        if (($productId=$post['id'])and($product=\common\models\Product::findOne($productId))) {
            $qty=empty($post['qty'])?1:$post['qty'];

//          Определяем какой товар. Аренда продажа
            if (array_key_exists('pricerent',$post)) {
                $type=OrderProduct::RENT;
            } elseif(array_key_exists('pricesale',$post))  {
                $type=OrderProduct::SALE;
            } else {
                $session->setFlash('error', 'Ошибка при добавлении товара в заказ. У товара не указана цена. ');
                return ['status' => 'error'];
            }
            if (($result=$currentOrder->addToBasket($productId,$qty,$type,$orderBlock_id,$parent_id))===true) {
                $out='Товар добавлен в заказ';
                $data=$out;
//                $data=$this->renderAjax('_orderHeaderBlock',['orders'=>Order::getActual()]);
            } else {
                $out="Ошибка при добавлени товара в заказ: ". $result;
            }
        }

        if (empty($data)) {
            $session->setFlash('error', $out);
            return ['status' => 'error'];
        } else {
            $session->setFlash('success', $out);
            return ['status' => 'success','data'=>$data];
        }

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
        $model = $this->findModel($id);
        $session = Yii::$app->session;

        $orderProductIds=OrderProduct::find()->select('id')->where(['order_id'=>$id])->asArray()->column();
        $movementIds=OrderProductAction::find()->select('movement_id')->where(['in', 'order_product_id', $orderProductIds])->asArray()->column();
        $query2 = Movement::find()->where(['in', 'id', $movementIds])->orderBy('dateTime');
        $dataProviderMovement=new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query2,
        ]);

        //провайдер платежей
        $сashIds=OrderCash::find()->select('cash_id')->where(['order_id'=>$id])->asArray()->column();
        $query3 = Cash::find()->where(['in', 'id', $сashIds])->orderBy('dateTime');
        $dataProviderCash=new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query3,
        ]);

//        $statuses=Status::find()->where(['hand'=>1])->orderBy('order')->all();
        $statuses=Status::find()->orderBy('order')->all();
        //Список пользователей
        $users=User::find()->all();

        //массив блоков
        $blocks=Block::find()->where(['client_id'=>$model->client_id])->indexBy('id')->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $session->setFlash('success', 'Заказ успешно сохранен');
                if ((Yii::$app->request->isAjax)) {
                    return $this->renderAjax('update', [
                        'model' => $model,
                        'dataProviderMovement' => $dataProviderMovement,
                        'blocks'=>$blocks,
                        'dataProviderCash' => $dataProviderCash,
                        'users'=>$users,
                        'statuses'=>$statuses,
                    ]);
                }
            } else {

//                $session->setFlash('error', 'Ошибка при сохранении заказа'.var_dump($model->firstErrors));
                $model = $this->findModel($id);
            }

        }
        return $this->render('update', [
            'model' => $model,
            'dataProviderMovement'=>$dataProviderMovement,
            'blocks'=>$blocks,
            'dataProviderCash' => $dataProviderCash,
            'users'=>$users,
            'statuses'=>$statuses,
        ]);
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

        if ($model=OrderProduct::findOne($orderProduct_id)) {
            if ($model->delete()) {
                $out='Позиция заказа удалена';
                $session->setFlash('success', $out);
                return ['status' => 'success','data'=>$out];
            } else {
                $out='Ошибка при удалении позиции заказа';
            }
        } else {
            $out='Ошибка. Не найдена позиция для удаления';
        }
        $session->setFlash('error', $out);
        return ['status' => 'error','data'=>$out];
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * контроллер возращает содеражание модальноо окна для подверждения операции движения с товарами в заказе
     */
    public function actionContentConfirmModalAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ((isset($_POST['keylist']))and(isset($_POST['operation']))) {
            $keys=$_POST['keylist'];
            if (!is_array($keys)) {
                return ['status' => 'error','data'=>'Ошибка при получение массива отмеченных строк'];
            }
            $query = OrderProduct::find()->where(['in', 'parent_id', $keys])->andWhere(['<>','type','collect']);
            $dataProvider = new ActiveDataProvider([
                'pagination' => [
                    'pageSize' => 5,
                ],
                'query' => $query,
            ]);
            $data = $this->renderAjax('_modalConfirmOperation', [
                'dataProvider'=>$dataProvider,
                'operation'=>$_POST['operation']
            ]);
            return ['status' => 'success','data'=>$data];
        }
    }

    /**
     * Добавление блока в заказ
     */
    public function actionAddBlockAjax($order_id,$block_name=null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $order=Order::findOne($order_id);
        if ($block_name) {
            $orderBlock=new OrderBlock(['name'=>$block_name,'order_id'=>$order->id]);
            if (!($orderBlock->save())) {
                return ['status' => 'error','data'=>$orderBlock->firstErrors];
            }
        }
        $orderBlocks=$order->getOrderProductsByBlock($orderBlock->id);

        $data= $this->renderAjax('_orderBlock',[
            'block'=>reset($orderBlocks)
        ]);
        return ['status' => 'success','html'=>$data];

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
                $out='Нельзя удалить блок с товарами';
                $status='error';
            } else {
                if ($model->delete()) {
                    $out='Блок удален';
                    $status='success';
                } else {
                    $out='Ошибка при удалении блока';
                    $status='error';
                }
            }
        } else {
            $out='Ошибка. Не найден блок для удаления';
            $status='error';
        }

        $session->setFlash($status, $out);
        return ['status' => $status,'data'=>$out];
    }

    public function actionAddCashModalAjax($order_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model=new Cash();
        $cashTypes = CashType::find()->all();

        $data = $this->renderAjax('_modalAddCash', [
            'model'=>$model,
            'order_id'=>$order_id,
            'cashTypes'=>$cashTypes
        ]);
        return ['status' => 'success','data'=>$data];
    }

    /**
     * Экспорт заказа в файл
     * @param $order_id
     */
    public function actionExport($order_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $url='https://ya.ru/';
        if ($url=self::exportToExcel($order_id)) {
            return ['status' => 'success','data'=>$url];
        } else {
            return ['status' => 'error','data'=>""];
        }

    }
    private function getDir()
    {
        $exportDir=\Yii::$app->params['exportDir'];
        if (!(is_dir(Yii::getAlias('@backend/web'. DIRECTORY_SEPARATOR . $exportDir))))
            mkdir(Yii::getAlias('@backend/web'. DIRECTORY_SEPARATOR . $exportDir), 0755, true);

        return $exportDir;
    }
    private function getPath($fileName)
    {
        return Yii::getAlias('@backend/web'. DIRECTORY_SEPARATOR . self::getDir()) . $fileName;
    }

    private function getUrl($fileName)
    {
        return Yii::$app->request->baseUrl . self::getDir() . $fileName;
    }
    private function exportToExcel($order_id)
    {
        $order=self::findModel($order_id);
        $orderBlocks=$order->getOrderProductsByBlock();
        $dateBegin=date_create($order->dateBegin);
        $dateBegin=date_format($dateBegin, 'Y-m-d');
        $fileName=$dateBegin.'_'.$order->name.'.xlsx';

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
        $styleSignature=array(
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
        $styleSignature2=array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ),
            'font' => array(
                'size'=>8,
            ),

        );

        $begin=$currentRow;
        $sheet->setCellValue('A'.$currentRow,'руководитель');
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow,'фотограф');
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow,'флорист');
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow,'организатор');
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow,'регистратор');
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow,'источник');
        $sheet->getStyle('A'. $begin.':F'.$currentRow)->applyFromArray($styleHeaderOrder);

        $currentRow++;
        $currentRow++;
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);
        $sheet->getStyle('B'. $begin.':F'.$currentRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ),
        ));
        $sheet->setCellValue('B' . $currentRow,'Приложение к договору №____ от   ___.___.______');

        $currentRow++;
        $currentRow++;

        $sheet->mergeCells('A' . $currentRow . ':F' . $currentRow);
        $sheet->getStyle('B'. $currentRow.':B'.$currentRow)->applyFromArray(
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
        $sheet->setCellValue('B'.$currentRow,'Бланк заказа');

        $currentRow++;
        $currentRow++;

        $begin=$currentRow;
        $sheet->setCellValue('B'.$currentRow,'дата');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $dateBegin=date_create($order->dateBegin);
        $sheet->setCellValue('C'.$currentRow,date_format($dateBegin, 'd.m.Y'));
        $currentRow++;
        $sheet->setCellValue('B'.$currentRow,'молодожены');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C'.$currentRow,$order->customer);
        $currentRow++;
        $sheet->setCellValue('B'.$currentRow,'контакты');
        $currentRow++;
        $sheet->setCellValue('B'.$currentRow,'место');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C'.$currentRow,$order->address);
        $sheet->getStyle('A'. $begin.':F'.$currentRow)->applyFromArray($styleHeaderOrder);

        $currentRow++;


        $mainItog=0;

        foreach ($orderBlocks as $orderBlock) {
            $currentRow++;
            $sheet->getStyle('A'. $currentRow.':F'.$currentRow)->applyFromArray($styleHeaderTable);
            $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
            $sheet->setCellValue('A'. $currentRow, $orderBlock['orderBlock']->name);
            $sheet->setCellValue('C'. $currentRow, 'цена');
            $sheet->setCellValue('D'. $currentRow, 'кол-во');
            $sheet->setCellValue('E'. $currentRow, 'период');
            $sheet->setCellValue('F'. $currentRow, 'сумма');

            $itog=0;

            $currentRow++;
            $begin=$currentRow;
            /** @var  $dataProvider  ArrayDataProvider*/
            $dataProvider=$orderBlock['dataProvider'];
            $orderProducts=$dataProvider->allModels;
            foreach ($orderProducts as $orderProduct) {

                if ($orderProduct['type']==\common\models\OrderProduct::COLLECT){
                    $name=$orderProduct['name'];
                } else {
                    $model=\common\models\Product::findOne($orderProduct['product_id']);
                    $name=$model->name;
                }
                $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
                $sheet->setCellValue('A' . $currentRow, $name);
                $sheet->setCellValue('C' . $currentRow, $orderProduct['cost']);
                $sheet->setCellValue('D' . $currentRow, $orderProduct['qty']);
                $summ=$orderProduct['cost']*$orderProduct['qty'];
                if ($orderProduct['period']) {
                    $summ=$summ*$orderProduct['period'];
                    $sheet->setCellValue('E' . $currentRow, $orderProduct['period']);
                }
                $itog+=$summ;
                $sheet->setCellValue('F' . $currentRow, $summ);
                $currentRow++;
            }
            $sheet->getStyle('A'. $begin.':F'.($currentRow-1))->applyFromArray(array(
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
            $sheet->setCellValue('E'. $currentRow, 'Итого:');
            $sheet->setCellValue('F'. $currentRow, $itog);
            $mainItog+=$itog;
            $currentRow++;
        }

        $currentRow++;
        $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
        $sheet->getStyle('B'. $currentRow.':E'.$currentRow)->applyFromArray(array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ),
        ));
        $sheet->setCellValue('B'. $currentRow, 'Общая стоимость товаров и услуг:');
        $sheet->setCellValue('F'. $currentRow, $mainItog);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells('A' . $currentRow . ':F' . ($currentRow+1));
        $sheet->getStyle('A'. $currentRow.':C'.$currentRow)->applyFromArray($styleMain);
        $sheet->setCellValue('A'. $currentRow, 'В стоимость проката конструкций, тканей и элементов декора не включены услуги по монтажу и демонтажу украшений.');
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
        $sheet->getStyle('A'. $currentRow.':C'.$currentRow)->applyFromArray($styleMain);
        $sheet->setCellValue('A'. $currentRow, 'С состоимостью согласен, количество предметов проката указано верно');

        $sheet->getStyle('D'. $currentRow.':F'.$currentRow)->applyFromArray($styleSignature);
        $currentRow++;
        $sheet->getStyle('D'. $currentRow.':F'.$currentRow)->applyFromArray($styleSignature2);
        $sheet->setCellValue('D'. $currentRow, 'дата');
        $sheet->setCellValue('E'. $currentRow, 'подпись');
        $sheet->setCellValue('F'. $currentRow, 'расшифровка');

        $writer = new Xlsx($spreadsheet);

//        TODO: Написать бы исключения
        $writer->save(self::getPath($fileName));
        return self::getUrl($fileName);
        return 'https://ya.ru/';
    }
}
