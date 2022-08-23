<?php

namespace rent\services\export;

use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\helpers\PaymentHelper;
use rent\helpers\TextHelper;
use Yii;



class PaymentExportService
{
    var array $alphabet=[
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R','S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    public function exportToExcel($dataProvider,array $balances=[])
    {
        $fileName = 'payments_' . date("d-m-y_h-i-s") . '.xlsx';

        $rows=$dataProvider->query->all();

        $spreadsheet = new Spreadsheet();
        $currentRow = 1;
        $sheet = $spreadsheet->getActiveSheet();


//      Определяем ширину
//        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(50);

//      Назначаем стили
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

        if (Yii::$app->user->can('admin')) {
            $sheet->setCellValue('A' . $currentRow, 'Остаток на ' . date('d.m.Y'));
            $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
            $sheet->setCellValue('C' . $currentRow, TextHelper::formatPrice($balances['all'], 'руб'));
            $currentRow++;
            $currentRow++;
            $i = 0;
            foreach (PaymentHelper::paymentTypeList() as $type_id => $item) {
                $sheet->setCellValue($this->alphabet[$i] . $currentRow, $item);
                $sheet->setCellValue($this->alphabet[$i] . ($currentRow + 1), TextHelper::formatPrice($balances[$type_id], 'руб'));
                $i++;
            }
            $currentRow++;
            $currentRow++;
            $currentRow++;
        }
        //      Шапка
        $sheet->setCellValue('A' . $currentRow, 'id');
        $sheet->setCellValue('B' . $currentRow, 'Дата');
        $sheet->setCellValue('C' . $currentRow, 'Название заказа');
        $sheet->setCellValue('D' . $currentRow, 'Тип платежа');
        $sheet->setCellValue('E' . $currentRow, 'Ответственный');
        $sheet->setCellValue('F' . $currentRow, 'Плательщик');
        $sheet->setCellValue('G' . $currentRow, 'Сумма');
        $sheet->setCellValue('H' . $currentRow, 'Примечание');
        $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->applyFromArray($styleHeaderTable);

        /** @var Payment $row */
        foreach ($rows as $row) {
            $currentRow++;
            $sheet->setCellValue('A' . $currentRow, $row->id);
            $sheet->setCellValue('B' . $currentRow, date('d.m.Y',$row->dateTime ));
            $sheet->setCellValue('C' . $currentRow, ($row->order_id?$row->order->name:''));
            $sheet->setCellValue('D' . $currentRow, ($row->type_id?PaymentHelper::paymentTypeName($row->type_id):''));
            $sheet->setCellValue('E' . $currentRow, $row->responsible->getShortName());
            $sheet->setCellValue('F' . $currentRow, $row->payerData->name);
            $sheet->setCellValue('G' . $currentRow, $row->sum);
            $sheet->setCellValue('H' . $currentRow, $row->note);
            $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->applyFromArray($styleHeaderTable);
        }
        $writer = new Xlsx($spreadsheet);
//        TODO: Написать бы исключения
        $writer->save(self::getPath($fileName));
        return self::getUrl($fileName);


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
}