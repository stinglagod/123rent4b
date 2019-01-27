<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 24.01.2019
 * Time: 10:38
 */
namespace common\models\behavior;
use common\models\Category;
use creocoder\nestedsets\NestedSetsBehavior;

class MyNestedSetsBehavior extends NestedSetsBehavior
{
        public function getPathAlias()
        {

            $parents=self::parents()->all();
            $pathAlias='';
            $first=true;
            foreach ($parents as $parent) {
//              Пропускаем корень
                if ($first) {
                    $first=false;
                    continue;
                }
                $pathAlias.='/'.$parent->name;
            }
            $pathAlias.= '/'.$this->owner->getAttribute('name');
            $pathAlias = self::checkAndCreatAlias(self::_conversion($pathAlias),$this->owner->getAttribute('id'));
            return $pathAlias;
        }

    /**
     * Преобразуем строку
     * TODO: сделать получше
     * @param $str
     * @return mixed
     */
    private static function _conversion($str)
    {
        $str=str_replace(' ', '_', $str);
        $str=str_replace('(', '', $str);
        $str=str_replace(')', '', $str);
        return str_replace('.', '', $str);
    }

    /**
     * Ищет одинаковый псевдоним, если есть меняет сещствующий
     * @param $alias
     * @return string
     */
    public static function checkAndCreatAlias($alias,$id)
    {
        if (($model=Category::find()->where(['alias'=>$alias])->one()) and($model->id!=$id)) {
            if (preg_match_all('/\d+$/', $alias, $matches)) {
//                return $matches[0];
//                \Yii::error($matches[0]);
                $newIndex=($matches[0][0]+1);
                $alias=preg_replace('/\d+$/', "$newIndex", $alias);
                $alias=self::checkAndCreatAlias($alias);
            } else {
                $alias.=1;
                $alias=self::checkAndCreatAlias($alias);
            }
        }
        return $alias;
    }
}