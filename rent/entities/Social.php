<?php

namespace rent\entities;

class Social
{
    public $urlInstagram;
    public $urlTwitter;
    public $urlFacebook;
    public $urlGooglePlus;
    public $urlVk;
    public $urlOk;

    public function __construct($urlInstagram, $urlTwitter, $urlFacebook, $urlGooglePlus, $urlVk, $urlOk)
    {
        $this->urlInstagram = $urlInstagram;
        $this->urlTwitter = $urlTwitter;
        $this->urlFacebook = $urlFacebook;
        $this->urlGooglePlus = $urlGooglePlus;
        $this->urlVk = $urlVk;
        $this->urlOk = $urlOk;
    }
}