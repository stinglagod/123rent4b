<?php
namespace console\controllers;

use rent\entities\Shop\Category\Category;
use rent\entities\Client\Client;
use rent\forms\manage\Shop\CategoryForm;
use rent\readModels\Shop\CategoryReadRepository;
use rent\services\import\FabrikaKarnavala\ProductImportService;
use rent\useCases\manage\Shop\CategoryManageService;
use Yii;
use yii\console\Controller;

class KarnavalnnController extends Controller
{

    private $serviceCategory;
    private $categories;
    private $import;

    public function __construct(
        $id,
        $module,
        CategoryManageService $serviceCategory,
        CategoryReadRepository $categories,
        ProductImportService $import,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->serviceCategory = $serviceCategory;
        $this->categories = $categories;
        $this->import = $import;
    }
    /**
     * Полный перенос
     */
    public function actionAll($client_id)
    {
        $this->updateSettings($client_id);

        if ($num=self::importCategories()) {
            echo "Import categories: $num\n";
        }
        if ($num=self::parsingDir()) {
            echo "Import Products: $num\n";
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

    /**
     * Проверка директории на наличие файлов обмена с 1с
     */
    public function actionChangeFrom1C($client_id)
    {
        $this->updateSettings($client_id);
        if ($num=self::parsingDir()) {
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
            if ($item[0]<1000) continue;

            if (!$this->categories->findByCode($item[0])){
                $form=new CategoryForm();
                $form->name=$item[3];
                $form->slug=$item[4];
                $form->code=$item[0];

                if ($item[1]) {
                    $parent=$this->categories->findByCode($item[1]);
                    $form->parentId=$parent->id;
                } else {
                    $form->parentId=$root->id;
                }
                $this->serviceCategory->create($form);
            }
        }
        return $num;
    }

    private function updateSettings($client_id):void
    {
        if (!$client=Client::findOne($client_id)) throw new \DomainException('Don not find client');

        Yii::$app->settings->initSite($client->getFirstSite()->id);

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

    private function parsingDir()
    {
        $this->import->parsingDir();
    }

}