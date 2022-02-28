<?php


namespace rent\settings;


class SettingOptions
{
    public ?int $client_id;
    public ?int $site_id;

    public function __construct(int $client_id=null, int $site_id=null)
    {
        $this->client_id=$client_id;
        $this->site_id=$site_id;
    }

}