<?php

namespace rent\services\manage\Import\FabrikaKarnavala;


use rent\entities\Meta;
use rent\entities\Shop\Characteristic;
use rent\entities\Shop\Product\Movement\Balance;
use rent\entities\Shop\Product\Photo;
use rent\entities\Shop\Product\Product;
use rent\readModels\Shop\CategoryReadRepository;
use rent\readModels\Shop\ProductReadRepository;
use rent\repositories\NotFoundException;
use rent\repositories\Shop\CategoryRepository;
use rent\repositories\Shop\ProductRepository;
use Yii;

define ( 'DEBUG', "1" );

class ProductImportService
{
    // директория, где находятся файлы для загрузки на сайт
    private $ftpDir="/uploads/1c/";
    // директория смонтированная удаленного сайта
    private $mountDir="/uploads/1c/remote";
    // массив с ошибками
    private $errors=array();

    private $logFile = 'import.log';
    //массив с результатами
    private $result= array(
        'addGood'=>0,
        'addCat'=>0,
        'updGood'=>0,
        'updCat'=>0,
        'delGood'=>0,
        'delCat'=>0,
        'error'=>0
    );
    private $products;
    private $readProduct;
    private $categories;

    public function __construct( ProductRepository $products, ProductReadRepository $readProduct, CategoryReadRepository $categories)
    {
        $this->products = $products;
        $this->readProduct = $readProduct;
        $this->categories = $categories;
        $this->ftpDir=Yii::getAlias('@runtime')."/uploads/1c/";
        $this->mountDir=Yii::getAlias('@runtime')."/uploads/1c/remote/";
        $this->logFile=$this->ftpDir.$this->logFile;

    }
    /**
     *  Просмотр папки и парсинг файлов
     */
    private $currentUploadFile;
    public function parsingDir()
    {
        $this->setLog('Начало парсинга',false);
        // копируем файлы с удаленного сервера
        if (!YII_DEBUG) {
//            var_dump(YII_DEBUG);
            $this->setLog('Монитуруем удаленный каталог',false);
            shell_exec('sshfs web@web1.zebra-nn.ru:/web/karnavalnn.ru/uploads/1c/debug/ '.$this->mountDir);
            shell_exec('mv -f '.$this->mountDir.'* '.$this->ftpDir);
            shell_exec('fusermount -u '.$this->mountDir);
        }
//        shell_exec('sshfs web@')
//      Проверяем есть ли директория
        if(!(is_dir($this->ftpDir))) {
            $this->setLog('Директория с файлами загрузки не доступна: '. $this->ftpDir);
            return false;
        }
        $this->setLog('Начало просмотра директории',false);
//      Проходим по файлам с расширением txt директории, отсортированной по имени
        $fileNames=scandir($this->ftpDir);
        foreach ($fileNames as $fileName) {
            $this->setLog('Текущий файл: '.$fileName,false);
            $fileName = $this->ftpDir.$fileName;
            if(preg_match('/\.+txt+$/', $fileName)) {
                $this->setLog('Открываем файл ',false);
                if (!filesize($fileName)) {
                    $this->setLog('Размер файла = 0. Пропускаем',true);
                    continue;
                }
                if ($file = fopen($fileName, "r")) {
                    $this->currentUploadFile=$fileName;
                    $action = trim(fgets($file));
                    $this->setLog('Первая строчка файла: '.$action,false);
                    $is_error=false;
                    if (method_exists($this,$action)) {
                        if ($this->{$action}($file)===false ){
                            $this->setLog('Ошибка парсинга: '.$action.' файла: '.$fileName, true);
                            $is_error=true;
                        };
                    } else {
                        $this->setLog('Для файла : '.$fileName . ' не найден метод: '.$action.' Пропуск',true);
                        $is_error=true;
                    }
                    fclose($file);
                    $this->removeFile($is_error);
                } else {
                    $this->setLog('Ошибка открытия файла: '.$this->ftpDir);
                    $this->removeFile(true);
                }
            }
            $this->currentUploadFile=null;
        };
        // Если произошли изменения пересобираем таблицу для поиска
        if (($this->result['addGood']) OR
            ($this->result['addCat']) OR
            ($this->result['updGood']) OR
            ($this->result['updCat']) OR
            ($this->result['delGood']) OR
            ($this->result['delGood'])) {

        }

        $this->setLog('Конец парсинга',false);
    }

### Parsing file
    /**
     * Добавление(редактирование) нового товара
     * @param bool|resource $file указатель на открытый файл
     */
    private function updGood ($file)
    {
        $item=array();
        $numImages=0;
        $this->setLog('Запуск: updGood',false);

        while (($buffer = fgets($file)) !== false) {
            $buffer=trim($buffer);
            if (preg_match('/^name\|(.+)/', $buffer,$matches)) {
                $item['name']=$matches[1];
            } else if (preg_match('/^artikul\|(.+)/', $buffer,$matches)) {
                $item['artikul']=$matches[1];
            } else if (preg_match('/^category\|(.+)/', $buffer,$matches)) {
                $item['category']=$matches[1];
            } else if (preg_match('/^shortStory\|(.+)/', $buffer,$matches)) {
                $text=$matches[1];
                //на случай если описание в несколько строк
                while (($buffer = fgets($file))!==false) {
                    if (preg_match('/^shortStoryEnd\|/', $buffer)) {
                        break;
                    }
                    $text.=$buffer;
                }
                $item['shortStory']=trim($text);
            } else if (preg_match('/^fullStory\|(.+)/', $buffer,$matches)) {
                $text=$matches[1]."\n";
                //на случай если описание в несколько строк
                while (($buffer = fgets($file))!==false) {
                    if (preg_match('/^fullStoryEnd\|/', $buffer)) {
                        break;
                    }
//                    if (preg_match('/^fullStoryEnd\|(.+)/', $buffer)) {
//                        break;
//                    }
                    $text.=$buffer;
                }
                $item['fullStory']=nl2br(trim($text));
            } else if (preg_match('/^tags\|(.+)/', $buffer,$matches)) {
                $item['tags'] = $matches[1];
            } else if (preg_match('/^youtube\|(.+)/', $buffer,$matches)) {
                $item['youtube'] = $matches[1];
            } else if (preg_match('/^yandex\|(.+)/', $buffer,$matches)) {
                $item['yandex'] = $matches[1];
            } else if (preg_match('/^tiu\|(.+)/', $buffer,$matches)) {
                $item['tiu'] = $matches[1];
            } else if (preg_match('/^composition\|(.+)/', $buffer,$matches)) {
                $item['composition'] = $matches[1];
            } else if (preg_match('/^material\|(.+)/', $buffer,$matches)) {
                $text=$matches[1];
                //на случай если описание в несколько строк
                while (($buffer = fgets($file))!==false) {
                    if (preg_match('/^materialEnd\|/', $buffer)) {
                        break;
                    }
                    $text.=$buffer;
                }
                $item['material']=trim($text);
            } else if (preg_match('/^proizvoditel\|(.+)/', $buffer,$matches)) {
                $item['proizvoditel'] = $matches[1];
            } else if (preg_match('/^country\|(.+)/', $buffer,$matches)) {
                $item['country|'] = $matches[1];
            } else if (preg_match('/^sposobKrepl\|(.+)/', $buffer,$matches)) {
                $item['sposobKrepl'] = $matches[1];
            } else if (preg_match('/^rk_heightDoll\|(.+)/', $buffer,$matches)) {
                $item['rk_heightDoll'] = $matches[1];
            } else if (preg_match('/^rk_heightMan\|(.+)/', $buffer,$matches)) {
                $item['rk_heightMan'] = $matches[1];
            } else if (preg_match('/^rk_weight\|(.+)/', $buffer,$matches)) {
                $item['rk_weight'] = $matches[1];
            } else if (preg_match('/^ir_size\|(.+)/', $buffer,$matches)) {
                $item['ir_size'] = $matches[1];
            } else if (preg_match('/^color\|(.+)/', $buffer,$matches)) {
                $item['color'] = $matches[1];
            } else if (preg_match('/^image\d*\|(.+)/', $buffer,$matches)) {
//                $numImages++;
//                $nameTraslTitle=mb_substr($this->transliterate(iconv("UTF-8","CP1251",$newGood['name'])),0,LENNAMETITLE,"CP1251")."_$numImages";
//                $pathSourceFile=$this->ftpDir.$matches[1];
                $item['images'][] = $this->ftpDir.$matches[1];
            } else if (preg_match('/^mark\|(.+)/', $buffer,$matches)) {
                $item['mark'] = $matches[1];
            } else if (preg_match('/^priceSell\|(.+)/', $buffer,$matches)) {
                $item['priceSell'] = $matches[1];
            } else if (preg_match('/^priceRent\|(.+)/', $buffer,$matches)) {
                $item['priceRent'] = $matches[1];
            } else if (preg_match('/^oldPriceSell\|(.+)/', $buffer,$matches)) {
                $item['oldPriceSell'] = $matches[1];
            } else if (preg_match('/^oldPriceRent\|(.+)/', $buffer,$matches)) {
                $item['oldPriceRent'] = $matches[1];
            } else if (preg_match('/^productionTime\|(.+)/', $buffer,$matches)) {
                $item['productionTime'] = $matches[1];
            } else if (preg_match('/^prop_man\|(.+)/', $buffer,$matches)) {
                $item['prop_man'] = $matches[1];
            } else if (preg_match('/^prop_woman\|(.+)/', $buffer,$matches)) {
                $item['prop_woman'] = $matches[1];
            } else if (preg_match('/^prop_child\|(.+)/', $buffer,$matches)) {
                $item['prop_child'] = $matches[1];
            } else if (preg_match('/^prop_print\|(.+)/', $buffer,$matches)) {
                $item['prop_print'] = $matches[1];
            } else if (preg_match('/^prop_mono\|(.+)/', $buffer,$matches)) {
                $item['prop_mono'] = $matches[1];
            } else if (preg_match('/^prop_sequin\|(.+)/', $buffer,$matches)) {
                $item['prop_sequin'] = $matches[1];
            }


        }
//        $this->setLog(var_dump($item),false);
        return $this->addUpdateGoodOnSite($item);
    }

    /**
     * Добавление(редактирование) новой категории
     * @param bool|resource $file указатель на открытый файл
     */
    private function updCategory ($file)
    {
        return true;
        $item = array();
        $this->setLog('Запуск: updCategory', false);

        while (($buffer = fgets($file)) !== false) {
            $buffer = trim($buffer);
            if (preg_match('/^name\|(.+)/', $buffer, $matches)) {
                $item['name'] = $matches[1];
            } else if (preg_match('/^categoryId\|(.+)/', $buffer,$matches)) {
                $item['categoryId'] = $matches[1];
            } else if (preg_match('/^parentId\|(.+)/', $buffer,$matches)) {
                $item['parentId'] = $matches[1];
            }
        }
//        $this->setLog('Название категории: '.$item['name'].' Кодировка: '. mb_detect_encoding($item['name']), false);
//        $this->setLog('Кодировка подключения: '.$this->arrMySQL['chr'], false);

//      Добавляем(редактируем) категорию в БД
        if ($item['categoryId']) {
            $tmp=explode(':',$item['categoryId']);
            $category_id=$tmp['0'];
            $order=$tmp['1'];
            $error=false;
            $message='';
//          Ищем категорию
            if ($result=$this->searchCatOnSite((int)$category_id)) {
                $error=false;
                $category = $result->fetch_array();
                $altName=$this->checkCategoryAltName($this->transliterate(mb_strtolower($item['name'])),$category_id);

                $result=$this->insUpdDelToDbMysql('UPDATE dle_category SET parentId = ?,name=?,alt_name=?,posi=? WHERE id = ?',
                    "issii",
                    isset($item['parentId'])?$item['parentId']:$category['parentId'],
                    $item['name']?$item['name']:$category['name'],
                    $item['name']?$altName:$category['alt_name'],
                    $order,
//                    $category['keywords'],
//                    $category['fulldescr'],
                    $category_id

                );
                if ($result) {
                    $error=false;
                    $message='Изменена категория: '.$category_id;
                    //              Чистим кеш
                    if (is_file($this->arrSiteInfo['dir'].'engine/cache/system/category.php')) {
                        unlink($this->arrSiteInfo['dir'].'engine/cache/system/category.php');
                    }
                } else {
                    $error=true;
                    $message='Ошибка при изменении категории: '.$category_id;
//                    $message=var_dump($result);
                }
            } else {
//                echo $this->transliterate($item['name']);
                //для создания имя обязательное
                if (empty($item['name'])) {
                    $error=true;
                    $message='Ошибка. Не заполнено поле name';
                } else {
                    $altName=$this->checkCategoryAltName($this->transliterate(mb_strtolower($item['name'])),$category_id);
                    $result = $this->insUpdDelToDbMysql('INSERT INTO dle_category(id,parentId,name,alt_name,keywords,fulldescr,posi) VALUES(?, ?,?,?,"","",?)',
                        "iissi",
                        $category_id,
                        $item['parentId']?$item['parentId']:0,
                        $item['name'],
                        $altName,
                        $order
                    );
                    if (!$result) {
                        $error=true;
                        $message='Ошибка при создании новой категории: '.$category_id;
                    } else {
                        $error=false;
                        $message='Добавлена новая категории: '.$category_id;
//                      Чистим кеш
                        if (is_file($this->arrSiteInfo['dir'].'engine/cache/system/category.php')) {
                            unlink($this->arrSiteInfo['dir'].'engine/cache/system/category.php');
                        }
                    }
                }
            }
            $this->setLog($message,$error);
            if ($error) {
                $this->result['error']++;
                return false;
            } else {
                $this->result['updCat']++;
                return true;
            }

        } else {
            $this->setLog('Ошибка. Не найдено поле "categoryId"');
            $this->result['error']++;
            return false;
        }

    }

    /**
     * Удаление  категории
     * @param bool|resource $file указатель на открытый файл
     */
    private function delCategory ($file)
    {
        return true;
        $item = array();
        $this->setLog('Запуск: delCategory', false);

        while (($buffer = fgets($file)) !== false) {
            $buffer = trim($buffer);
            if (preg_match('/^categoryId\|(.+)/', $buffer,$matches)) {
                $item['categoryId'] = $matches[1];
            }
        }
        $this->setLog(print_r($item),false);
        if ($item['categoryId']) {
            $message=null;
            $error=false;
            if ($result=$this->searchCatOnSite($item['categoryId'])) {
                if ($this->insUpdDelToDbMysql('DELETE FROM dle_category WHERE id=?',"i",$item['categoryId'])) {
                    $error=true;
                    $message='Удалена категория: '.$item['categoryId'];
                    $this->result['delCat']++;
                } else {
                    $error=true;
                    $message='Ошибка при удалении в БД категории: '.$item['categoryId'];
                    $this->result['error']++;
                }
            } else {
                $error=true;
                $message='Ошибка. На сайте не найдена категория: '.$item['categoryId'];
                $this->result['error']++;
            }

            $this->setLog($message,$error);
            $this->removeFile($error);
        } else {
            $this->setLog('Ошибка. Не найдено поле "category_id"');
            $this->removeFile(true);
            $this->result['error']++;
        }
        return !$error;
    }

    /**
     * Удаление Товара
     * @param bool|resource $file указатель на открытый файл
     */
    private function delGood ($file)
    {
        $item = array();
        $this->setLog('Запуск: delGood', false);

        while (($buffer = fgets($file)) !== false) {
            $buffer = trim($buffer);
            if (preg_match('/^artikul\|(.+)/', $buffer,$matches)) {
                $item['artikul'] = $matches[1];
            }
        }
        if ($item['artikul']) {
            if ($result = $this->searchGoodOnSite($item['artikul'])) {
                if ($this->insUpdDelToDbMysql('DELETE FROM dle_post WHERE artikul=?', "i", $item['artikul'])) {
                    $error = true;
                    $message = 'Удален товар: ' . $item['artikul'];
                    $this->result['delGood']++;
                } else {
                    $error = true;
                    $message = 'Ошибка при удалении в БД товара: ' . $item['artikul'];
                    $this->result['error']++;
                }
            } else {
                $error = true;
                $message = 'Ошибка. На сайте не найден товар с артиклом: ' . $item['artikul'];
                $this->result['error']++;
            }
            $this->setLog($message,$error);
            $this->removeFile($error);
        } else {
            $this->setLog('Ошибка. Не найдено поле "artikul"');
            $this->removeFile(true);
            $this->result['error']++;
        }

        return !$error;
    }

    /**
     * Изменение баланса
     * @param bool|resource $file указатель на открытый файл
     */
    private function chngBalance ($file)
    {
        $item = array();
        $this->setLog('Запуск: chngBalance', false);

        while (($buffer = fgets($file)) !== false) {
            $buffer = trim($buffer);
            if (preg_match('/^artikul\|(.+)/', $buffer,$matches)) {
                $item['artikul'] = $matches[1];
            } else if (preg_match('/^balance\|(.+)/', $buffer,$matches)) {
                $item['ostatok'] = $matches[1];
            }
        }
        return $this->addUpdateGoodOnSite($item,false);
    }

### Private
    /**
     * Запись вывод лога
     */
    private function setLog($message,$is_error=true)
    {
        $messageWithDate=date('Y-m-d H:i:s') . ' '.$message;
        if (DEBUG) {
            echo $messageWithDate."\n";
            if ($is_error===false) {
                $this->errors[]=$message;
                file_put_contents($this->logFile, $messageWithDate . PHP_EOL, FILE_APPEND);
            }
        }
        if ($is_error) {
            if (!DEBUG) echo $messageWithDate."\n";
            $this->errors[]=$message;
            file_put_contents($this->logFile, $messageWithDate . PHP_EOL, FILE_APPEND);
        }

    }

    /**
     * Удаляем файл. В случае ошибки перемещаемс в папку /error/
     * @param bool $is_error        с ошибкой?
     */
    private function removeFile ($is_error=false)
    {
        //Находим директорюи по имени файла
        $pathinfo=pathinfo($this->currentUploadFile);
        $dir=$pathinfo['dirname'].'/'.$pathinfo['filename'];
//        $is_error=true;
        if ($is_error) {
            $errorPath = $this->ftpDir . 'error';
            if (!is_dir($errorPath)) {
                mkdir($errorPath);
            }
//            return true;
            if (rename($this->currentUploadFile, $errorPath . '/' . basename($this->currentUploadFile))) {
                if (is_dir($dir)) {
                    rename($dir, $errorPath . '/' . $pathinfo['filename']);
                }
                $this->setLog('Перемещение: ' . $this->currentUploadFile . ' в ' . $errorPath . '/' . basename($this->currentUploadFile), false);
            } else {
                $this->setLog(' Ошибка при перемещении: ' . $this->currentUploadFile . ' в ' . $errorPath . '/' . basename($this->currentUploadFile), true);
            }
        } else if (DEBUG) {
            $errorPath = $this->ftpDir . 'debug';
            if (!is_dir($errorPath)) {
                mkdir($errorPath);
            }
//            return true;
            if (rename($this->currentUploadFile, $errorPath . '/' . basename($this->currentUploadFile))) {
                if (is_dir($dir)) {
                    rename($dir, $errorPath . '/' . $pathinfo['filename']);
                }
                $this->setLog('Перемещение: ' . $this->currentUploadFile . ' в ' . $errorPath . '/' . basename($this->currentUploadFile), false);
            } else {
                $this->setLog(' Ошибка при перемещении: ' . $this->currentUploadFile . ' в ' . $errorPath . '/' . basename($this->currentUploadFile), true);
            }
        } else {
            if (unlink($this->currentUploadFile)) {
                $this->setLog('Удален файл: '.$this->currentUploadFile,false);
            } else {
                $this->setLog('Ошибка при удалеи файла: '.$this->currentUploadFile,true);
            }
            //      Удаляем папку по имени файла
            if (is_dir($dir)) {
                if ($this->delTree($dir)) {
                    $this->setLog('Удалена директория: '.$pathinfo['dirname'].'/'.$pathinfo['filename'],false);
                } else {
                    $this->setLog('Ошибка при удалении директории: '.$pathinfo['dirname'].'/'.$pathinfo['filename'],true);
                }
            }
        }



    }

    /**
     * Удаляем директорию с содержимым
     * @param $dir
     * @return bool
     */
    private function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * Добавляем или редактируем товар на сайт
     * @param array $good массив с полями товара
     * @param bool $addnew - добавлять ли новый, если нет товара
     * @return bool
     */
    public function addUpdateGoodOnSite($good,$addnew=true)
    {
        $error=false;
        $message=true;
        if (empty($good['artikul'])) {
            $this->setLog('Ошибка. не указан артикул товара',true);
            return false;
        }
//      Если shortstory не заполнено, тогда берем информацию из fullstory
//        if (empty($good['shortStory'])) $good['shortStory']=$good['fullStory'];


//      Добавляем или редактируем
        if ($product=$this->readProduct->findByCode($good['artikul'])) {
            $this->setLog('Редактируем новый товар с артикулом: '.$good['artikul'],false);

            if (array_key_exists('shortStory',$good))       $product->description=$good['shortStory'];

            if (array_key_exists('priceSell',$good))        $product->priceSale_new=$good['priceSell'];
            if (array_key_exists('priceRent',$good))        $product->priceRent_new=$good['priceRent'];
            if (array_key_exists('oldPriceSell',$good))     $product->priceSale_old=$good['oldPriceSell'];
            if (array_key_exists('oldPriceRent',$good))     $product->priceRent_old=$good['oldPriceRent'];

            if (!empty($good['images'])) {
                $product->photos=[];
                $this->products->save($product);
                $product->main_photo_id=$this->addPhotos($good['images'],$product->id, $product->name);

            }
            if (array_key_exists('youtube',$good))          $product->setValue(5,$good['youtube']);
            if (array_key_exists('composition',$good))      $product->setValue(6,$good['composition']);
            if (array_key_exists('material',$good))         $product->setValue(7,$good['material']);
            if (array_key_exists('proizvoditel',$good))     $product->setValue(8,$good['proizvoditel']);
            if (array_key_exists('country',$good))          $product->setValue(9,$good['country']);
            if (array_key_exists('sposobKrepl',$good))      $product->setValue(10,$good['sposobKrepl']);
            if (array_key_exists('rk_heightDoll',$good))    $product->setValue(11,$good['rk_heightDoll']);
            if (array_key_exists('rk_heightMan',$good))     $product->setValue(12,$good['rk_heightMan']);
            if (array_key_exists('rk_weight',$good))        $product->setValue(13,$good['rk_weight']);
            if (array_key_exists('ir_size',$good))          $product->setValue(14,$good['ir_size']);
            if (array_key_exists('color',$good))            $product->setValue(15,$good['color']);

            if (array_key_exists('mark',$good))             $product->setValue(16,$good['mark']);
            if (array_key_exists('productionTime',$good))   $product->setValue(17,$good['productionTime']);
            if (array_key_exists('prop_man',$good))         $product->setValue(18,$good['prop_man']);
            if (array_key_exists('prop_woman',$good))       $product->setValue(19,$good['prop_woman']);
            if (array_key_exists('prop_child',$good))       $product->setValue(20,$good['prop_child']);
            if (array_key_exists('prop_print',$good))       $product->setValue(21,$good['prop_print']);
            if (array_key_exists('prop_sequin',$good))      $product->setValue(22,$good['prop_sequin']);
            if (array_key_exists('prop_mono',$good))        $product->setValue(23,$good['prop_mono']);

            if (array_key_exists('ostatok',$good))          $product->addBalanceCorrect($good['ostatok']);

            $this->products->save($product);
            $this->setLog('Успешно изменен товар с артикулом: '.$good['artikul'],true);
            $this->result['updGood']++;
            return true;

        } else if ($addnew) {
            //на тот случай, если изменения и товара нет
            if (    !(array_key_exists('name',$good)) or
                !(array_key_exists('category',$good)) or
                !(array_key_exists('fullStory',$good))
            ) {
                return true;
            }

            $category=null;
            if ($good['category']) {
                $arrTmp=explode(',',$good['category']);
                $categoryTmp=array();
                foreach ($arrTmp as $item) {
                    $arrTmp2=explode(':',$item);
                    $category[]=$arrTmp2[0];
                }
            }

            if (!$mainCategory=$this->categories->findByCode($category[0])) {
                throw new NotFoundException('Category is not found.');
            }

            //          Добавляем
            $product = Product::create(
                null,
                $mainCategory->id,
                $good['artikul'],
                $good['name'],
                $good['fullStory'],
                new Meta(
                    $good['name'],
                    $good['fullStory'],
                    ''
                )
            );
            if (array_key_exists('priceSell',$good))        $product->priceSale_new=$good['priceSell'];
            if (array_key_exists('priceRent',$good))        $product->priceRent_new=$good['priceRent'];
            if (array_key_exists('oldPriceSell',$good))     $product->priceSale_old=$good['oldPriceSell'];
            if (array_key_exists('oldPriceRent',$good))     $product->priceRent_old=$good['oldPriceRent'];
            if (($good['images'])) {
                $product->photos=[];
                $this->products->save($product);
                $product->main_photo_id=$this->addPhotos($good['images'],$product->id, $product->name);

            }
            if (array_key_exists('youtube',$good))          $product->setValue(5,$good['youtube']);
            if (array_key_exists('composition',$good))      $product->setValue(6,$good['composition']);
            if (array_key_exists('material',$good))         $product->setValue(7,$good['material']);
            if (array_key_exists('proizvoditel',$good))     $product->setValue(8,$good['proizvoditel']);
            if (array_key_exists('country',$good))          $product->setValue(9,$good['country']);
            if (array_key_exists('sposobKrepl',$good))      $product->setValue(10,$good['sposobKrepl']);
            if (array_key_exists('rk_heightDoll',$good))    $product->setValue(11,$good['rk_heightDoll']);
            if (array_key_exists('rk_heightMan',$good))     $product->setValue(12,$good['rk_heightMan']);
            if (array_key_exists('rk_weight',$good))        $product->setValue(13,$good['rk_weight']);
            if (array_key_exists('ir_size',$good))          $product->setValue(14,$good['ir_size']);
            if (array_key_exists('color',$good))            $product->setValue(15,$good['color']);

            if (array_key_exists('mark',$good))             $product->setValue(16,$good['mark']);
            if (array_key_exists('productionTime',$good))   $product->setValue(17,$good['productionTime']);
            if (array_key_exists('prop_man',$good))         $product->setValue(18,$good['prop_man']);
            if (array_key_exists('prop_woman',$good))       $product->setValue(19,$good['prop_woman']);
            if (array_key_exists('prop_child',$good))       $product->setValue(20,$good['prop_child']);
            if (array_key_exists('prop_print',$good))       $product->setValue(21,$good['prop_print']);
            if (array_key_exists('prop_sequin',$good))      $product->setValue(22,$good['prop_sequin']);
            if (array_key_exists('prop_mono',$good))        $product->setValue(23,$good['prop_mono']);

            if (array_key_exists('ostatok',$good))          $product->addBalanceCorrect($good['ostatok']);

            $this->products->save($product);
            $this->setLog('Успешно добавлен товар с артикулом: '.$good['artikul'],true);
            $this->result['addGood']++;

        } else {
            $this->setLog('Ошибка не найден товар с артикулом: '.$good['artikul'],true);
            return false;
        }

    }
### Private
    private function addPhotos($files,$product_id,$name): ?int
    {
        $num=1;
        $mainPhoto_id=null;
        foreach ($files as $file) {
            $path_info = pathinfo($file);
            /** @var Photo $newPhoto */
            $newPhoto=new Photo();
//            $newPhoto->id=$file->id;
            $newPhoto->file=$name.'_'.$num.'.'.$path_info['extension'];
            $newPhoto->product_id=$product_id;
            $newPhoto->sort=$num;


            if ($newPhoto->save()) {
                $newPath=Yii::getAlias('@staticRoot/origin/products/'.self::makeIdPath($newPhoto->id).'/');
                echo $newPath;echo "\n";
                if (!is_dir($newPath))
                    mkdir($newPath,0775,true);
                copy($file,$newPath.$newPhoto->id.'.'.$path_info['extension']);
//                $newPhoto->createThumbs();
            }
            if ($num==1) {
                $mainPhoto_id=$newPhoto->id;
            }
            $num++;
        }
        return $mainPhoto_id;
    }
    /**
     * @param integer $id
     * @return string
     */
    protected static function makeIdPath($id)
    {
        $id = is_array($id) ? implode('', $id) : $id;
        $length = 10;
        $id = str_pad($id, $length, '0', STR_PAD_RIGHT);

        $result = [];
        for ($i = 0; $i < $length; $i++) {
            $result[] = substr($id, $i, 1);
        }

        return implode('/', $result);
    }
}