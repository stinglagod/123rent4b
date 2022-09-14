<?php

namespace rent\services\export;

use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Order;
use Yii;



class OrderExportService
{
    public function exportOrderToExcel(Order $order)
    {
        $dateBegin = date('Y-m-d',$order->date_begin);
        $fileName = $dateBegin . '_' . $order->name . '.xlsx';
        $fileName= str_replace('/','',$fileName);

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
        $sheet->setCellValue('C' . $currentRow, date( 'd.m.Y',$order->date_begin));
        $currentRow++;
        $sheet->setCellValue('B' . $currentRow, 'молодожены');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C' . $currentRow, $order->customerData->name);
        $currentRow++;
        $sheet->setCellValue('B' . $currentRow, 'контакты');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C' . $currentRow, $order->customerData->phone);
        $currentRow++;
        $sheet->setCellValue('B' . $currentRow, 'место');
        $sheet->mergeCells('C' . $currentRow . ':F' . $currentRow);
        $sheet->setCellValue('C' . $currentRow, $order->deliveryData->address);
        $sheet->getStyle('A' . $begin . ':F' . $currentRow)->applyFromArray($styleHeaderOrder);

        $currentRow++;
        $sheet->setCellValue('H' . $currentRow, 'примечание');


        $mainItog = 0;

        foreach ($order->blocks as $block) {
            $currentRow++;
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleHeaderTable);
            $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, $block->name);
            $sheet->setCellValue('C' . $currentRow, 'цена');
            $sheet->setCellValue('D' . $currentRow, 'кол-во');
            $sheet->setCellValue('E' . $currentRow, 'период');
            $sheet->setCellValue('F' . $currentRow, 'сумма');


            $currentRow++;
            $begin = $currentRow;
            foreach ($block->children as $child) {

                $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
                $sheet->setCellValue('A' . $currentRow, $child->name);
                $sheet->setCellValue('C' . $currentRow, $child->price);
                $sheet->setCellValue('D' . $currentRow, $child->qty);
                $summ = $child->cost;
                if ($child->isRent()) {
                    $sheet->setCellValue('E' . $currentRow, $child->periodData->qty);
                }
                if ($child->note) {
                    $sheet->setCellValue('H' . $currentRow, $child->note);
                }
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
            $sheet->setCellValue('F' . $currentRow, $block->cost);
            $mainItog += $block->cost;
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
        foreach ($order->services as $service) {
            $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
            $sheet->getStyle('B' . $currentRow . ':E' . $currentRow)->applyFromArray($styleItogo);
            $sheet->setCellValue('B' . $currentRow, $service->name);
            $sheet->setCellValue('F' . $currentRow, $service->cost);
            $mainItog += $service->cost;
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
    }

    public function exportOrdersToExcel($dataProvider)
    {
        $fileName = 'orders_' . date("d-m-y_h-i-s") . '.xlsx';

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
//            $dateBegin = date_create($row->dateBegin);
            $sheet->setCellValue('C' . $currentRow, date('d.m.Y',$row->date_begin ));
            $sheet->setCellValue('F' . $currentRow, $row->getTotalCost());
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleHeaderTable);
            /** @var OrderItem $orderBlock */
            foreach ($row->blocks as $orderBlock) {
                $currentRow++;
                $sheet->mergeCells('A' . $currentRow . ':F' . ($currentRow));
                $sheet->setCellValue('A' . $currentRow, $orderBlock->name);
                $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleOrderBlock);
                foreach ($orderBlock->children as $orderProduct) {

                    $currentRow++;
                    $sheet->mergeCells('A' . $currentRow . ':B' . ($currentRow));
                    $sheet->setCellValue('A' . $currentRow, $orderProduct->name);
                    $sheet->setCellValue('C' . $currentRow, $orderProduct->cost);
                    $sheet->setCellValue('D' . $currentRow, $orderProduct->qty);
                    $sheet->setCellValue('E' . $currentRow, $orderProduct->periodData?$orderProduct->periodData->qty:'');
                    $sheet->setCellValue('F' . $currentRow, $orderProduct->getCost());
//                    $sheet->setCellValue('E' . $currentRow, $orderProduct->name);
//                    $sheet->setCellValue('F' . $currentRow, $orderProduct->name);
//                    $sheet->setCellValue('A' . $currentRow, $orderProduct->name);
                }
            }
            // Выводим услуги если есть
            if ($services=$row->services) {
                $currentRow++;
                $sheet->mergeCells('A' . $currentRow . ':F' . ($currentRow));
                $sheet->setCellValue('A' . $currentRow, "УСЛУГИ");
                $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($styleOrderBlock);
                /** @var OrderItem $service */
                foreach ($services as $service) {
                    $currentRow++;
                    $sheet->mergeCells('A' . $currentRow . ':B' . ($currentRow));
                    $sheet->setCellValue('A' . $currentRow, $service->name);
                    $sheet->setCellValue('C' . $currentRow, $service->price);
//                    $sheet->setCellValue('D' . $currentRow, $service['qty']);
                    $sheet->setCellValue('F' . $currentRow, $service->getCost());
                }
            }

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