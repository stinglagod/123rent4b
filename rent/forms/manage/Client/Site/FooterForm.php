<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\Footer;
use rent\forms\CompositeForm;
use rent\forms\manage\Client\Site\Footer\FooterCategoryForm;
use rent\forms\manage\Client\Site\MainPage\MainPageCategoryForm;

/**
 * @property \rent\forms\manage\Client\Site\MainPage\MainPageCategoryForm[] categories
 **/

class FooterForm extends CompositeForm
{
    public $tmp;

    public function __construct(Footer $footer=null,$config = [])
    {
        if ($footer) {
            if ($footer->categories) {
                $key=0;
                $this->categories=array_map(function ($item) use (&$key)  {
                    return new FooterCategoryForm($item['category'],$key++);
                }, $footer->categories);
            }

        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['tmp', 'safe'],
        ];
    }
    protected function internalForms(): array
    {
        return ['categories'];
    }
}