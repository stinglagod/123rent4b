<?php

namespace rent\entities\abstracts;

use yii\base\Behavior;
use yii\helpers\Json;
use ReflectionClass;
use ReflectionProperty;

class JsonAbstract
{
    public function set($data)
    {
        foreach ($data AS $key => $value) {
            if (isset($this->{$key})) {
                $this->{'old' . $key} = $this->{$key};
            }
            $this->{$key} = $value;
        }
    }

    public function save()
    {
        return;
    }

    public function getJson()
    {
//        $this->save();
        return Json::encode($this->getArray());
    }

### Private
    /**
     * Очищаем от ненужного:
     * - аттрибутов old
     */
    private function removeOldAttributes(): void
    {
        //очищаем от old аттрибутов
        foreach ($this as $key => $value) {
            if (empty($this->{$key})) {
                unset($this->{$key});
            }
            if (strripos($key, 'old') === false) {
                continue;
            }
            unset($this->{$key});
        }
    }

    private function getArray()
    {
        $this->removeOldAttributes();
        return array_filter(get_object_vars($this));
    }
}