<?php
namespace console\controllers;

use common\models\Cash;
use common\models\OrderBlock;
use common\models\OrderProduct;
use rent\cart\CartItem;
use rent\entities\Shop\Category;
use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\DeliveryData;
use rent\entities\Shop\Order\Item\ItemBlock;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Order\Order;
use rent\entities\Client\Client;
use rent\entities\Meta;
use rent\entities\Shop\Characteristic;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Order\Status;
use rent\entities\Shop\Service;
use rent\entities\Shop\Product\Movement\Action;
use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Shop\Product\Photo;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use rent\forms\manage\Shop\CategoryForm;
use rent\forms\manage\Shop\Order\OrderCreateForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\forms\manage\Shop\Product\PhotosForm;
use rent\readModels\Shop\CategoryReadRepository;
use rent\readModels\Shop\OrderReadRepository;
use rent\repositories\Shop\CharacteristicRepository;
use rent\services\manage\Shop\CategoryManageService;
use rent\services\manage\Shop\OrderManageService;
use rent\services\manage\Shop\ProductManageService;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class KarnavalnnController extends Controller
{

    private $serviceCategory;
    private $categories;

    public function __construct(
        $id,
        $module,
        CategoryManageService $serviceCategory,
        CategoryReadRepository $categories,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->serviceCategory = $serviceCategory;
        $this->categories = $categories;
    }
    /**
     * Полный перенос
     */
    public function actionAll($client_id)
    {
        $this->updateSettings($client_id);

        if ($num=self::importCategories($client_id)) {
            echo "Import categories: $num\n";
        }
        if ($num=self::importCharacteristics($client_id)) {
            echo "Import Characteristics: $num\n";
        }
        if ($num=self::importTags($client_id)) {
            echo "Import Tags: $num\n";
        }
        if ($num=self::importProducts($client_id)) {
            echo "Import products: $num\n";
        }
        if ($num=self::importMovements($client_id)) {
            echo "Import movements: $num\n";
        }
        if ($num=self::importService($client_id)) {
            echo "Import services: $num\n";
        }
        if ($num=self::importBlock($client_id)) {
            echo "Import blocks: $num\n";
        }
        if ($num=self::importOrder($client_id)) {
            echo "Import orders: $num\n";
        }

    }
    /**
     * Перенос категорий с сайта karnavalnn.ru
     */
    public function actionCategories($client_id)
    {
        $this->updateSettings($client_id);
        if ($num=self::importCategories()) {
            echo "Import categories: $num\n";
        }

    }
############################################################
    private function importCategories():int
    {
        $num=0;
//        var_dump(Yii::getAlias('@runtime').'/dle_category.csv');
        $data = $this->kama_parse_csv_file( Yii::getAlias('@runtime').'/dle_category.csv'   );
//        var_dump($data);exit;
        $root=Category::getRoot();
        foreach ($data as $item) {
            if ($item[0]=='id') {
                continue;
            }

            if (!$this->categories->findBySlug($item[4])){
//                var_dump($item);exit;
                $form=new CategoryForm();
                $form->name=$item[3];
                $form->slug=$item[4];
                if ($item[1]) {
                    $parent=$this->categories->findBySlug($this->searchCategory($data,$item[1]));
                    var_dump($this->searchCategory($data,$item[1]));exit;
                    $form->parentId=$parent->id;
                } else {
                    $form->parentId=$root->id;
                }

//                var_dump($form);exit;
                $this->serviceCategory->create($form);
            }
        }
        return $num;
    }
    private function searchCategory($data,$category_id):string
    {
        foreach ($data as $item) {
            if ($item[1]==$category_id) {
                return $item[4];
            }
        }
    }

    private function updateSettings($client_id):void
    {
        if (!$client=Client::findOne($client_id)) throw new \DomainException('Don not find client');

        Yii::$app->params['siteId']=$client->getFirstSite()->id;
        Yii::$app->params['timezone']=$client->getFirstSite()->timezone;

        if ($timezone=Yii::$app->params['timezone']) {
            date_default_timezone_set($timezone);
        } else {
            date_default_timezone_set('UTC');
        }

    }

    ## Читает CSV файл и возвращает данные в виде массива.
    ## @param string $file_path Путь до csv файла.
    ## string $col_delimiter Разделитель колонки (по умолчанию автоопределине)
    ## string $row_delimiter Разделитель строки (по умолчанию автоопределине)
    ## ver 6
    private function kama_parse_csv_file( $file_path, $file_encodings = ['cp1251','UTF-8'], $col_delimiter = '', $row_delimiter = "" ){

        if( ! file_exists($file_path) )
            return false;

        $cont = trim( file_get_contents( $file_path ) );

        $encoded_cont = mb_convert_encoding( $cont, 'UTF-8', mb_detect_encoding($cont, $file_encodings) );

        unset( $cont );

        // определим разделитель
        if( ! $row_delimiter ){
            $row_delimiter = "\r\n";
            if( false === strpos($encoded_cont, "\r\n") )
                $row_delimiter = "\n";
        }

        $lines = explode( $row_delimiter, trim($encoded_cont) );
        $lines = array_filter( $lines );
        $lines = array_map( 'trim', $lines );

        // авто-определим разделитель из двух возможных: ';' или ','.
        // для расчета берем не больше 30 строк
        if( ! $col_delimiter ){
            $lines10 = array_slice( $lines, 0, 30 );

            // если в строке нет одного из разделителей, то значит другой точно он...
            foreach( $lines10 as $line ){
                if( ! strpos( $line, ',') ) $col_delimiter = ';';
                if( ! strpos( $line, ';') ) $col_delimiter = ',';

                if( $col_delimiter ) break;
            }

            // если первый способ не дал результатов, то погружаемся в задачу и считаем кол разделителей в каждой строке.
            // где больше одинаковых количеств найденного разделителя, тот и разделитель...
            if( ! $col_delimiter ){
                $delim_counts = array( ';'=>array(), ','=>array() );
                foreach( $lines10 as $line ){
                    $delim_counts[','][] = substr_count( $line, ',' );
                    $delim_counts[';'][] = substr_count( $line, ';' );
                }

                $delim_counts = array_map( 'array_filter', $delim_counts ); // уберем нули

                // кол-во одинаковых значений массива - это потенциальный разделитель
                $delim_counts = array_map( 'array_count_values', $delim_counts );

                $delim_counts = array_map( 'max', $delim_counts ); // берем только макс. значения вхождений

                if( $delim_counts[';'] === $delim_counts[','] )
                    return array('Не удалось определить разделитель колонок.');

                $col_delimiter = array_search( max($delim_counts), $delim_counts );
            }

        }

        $data = [];
        foreach( $lines as $key => $line ){
            $data[] = str_getcsv( $line, $col_delimiter ); // linedata
            unset( $lines[$key] );
        }

        return $data;
    }


}