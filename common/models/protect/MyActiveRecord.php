<?php
namespace common\models\protect;

use common\models\File;
use yii\db\ActiveRecord;
use yii\db\Query;

class MyActiveRecord extends ActiveRecord
{
    /**
     * Возращаемс хеш любой модели
     * @return string hash
     */
    protected function getHash()
    {
        return md5(get_class($this) . '-' . $this->id);
    }

    protected function getQtyFiles()
    {
        return File::getQtyFiles($this->hash);
    }

//    TODO: сделать выборку файлов по расширениям
    public function getFiles($type=null)
    {
        return File::find()->where(['hash'=>$this->hash])->all();

    }
}