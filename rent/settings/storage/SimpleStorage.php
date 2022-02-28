<?php


namespace rent\settings\storage;


use rent\settings\SettingOptions;

class SimpleStorage implements StorageInterface
{

    private SettingOptions $settingOptions;

    public function __construct(int $clientId=null,int $siteId=null)
    {
        $this->settingOptions=new SettingOptions($clientId,$siteId);
    }

    public function load(): SettingOptions
    {
        return $this->settingOptions;
    }

    public function save(SettingOptions $settingOptions): void
    {
        return;
    }
}