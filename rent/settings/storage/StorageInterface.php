<?php

namespace rent\settings\storage;

use rent\cart\CartItem;
use rent\settings\SettingOptions;

interface StorageInterface
{

    /**
     * @return array
     */
    public function load(): SettingOptions;

    public function save(SettingOptions $settingOptions): void;
}