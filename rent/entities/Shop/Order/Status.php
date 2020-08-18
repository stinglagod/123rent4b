<?php

namespace rent\entities\Shop\Order;

class Status
{
    const NEW = 1;                      //Новый
    const NEW_BY_CUSTOMER=2;            //Новый созданный заказчиком через сайта
    const ESTIMATE = 3;                 //Составлена смена
    const PART_ISSUE=4;                 //Частично выданы товары
    const ISSUE=5;                      //Товары выданы полностью
    const PART_RETURN=6;                //Частично возращены товары
    const RETURN=7;                     //Товары возращены полностью
    const COMPLETED = 8;                //Завершен
    const CANCELLED = 9;                //Отменен
    const CANCELLED_BY_CUSTOMER = 10;   //Отменен Заказчиком через сайт

    public $value;
    public $created_at;

    public function __construct($value, $created_at)
    {
        $this->value = $value;
        $this->created_at = $created_at;
    }
}