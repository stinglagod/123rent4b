<?php

namespace rent\entities\Client\Site;

use rent\entities\Shop\Category\Category;
use rent\forms\manage\Client\Site\FooterForm;
use rent\entities\abstracts\JsonAbstract;
use yii\helpers\Json;


class Footer extends JsonAbstract
{
    const CATEGORY_COUNT=5;
    public $categories;
    public $categoryReadRepository;

    public function __construct(string $json=null, FooterForm $footerForm=null)
    {

        if ($json) {
            $this->set(Json::decode($json));
        } elseif ($footerForm) {
            foreach ($footerForm->categories as $category) {
                $this->categories[]=[
                    'category_id'=>$category->category_id,
                    'category'=>$category->category,
                ];
            }
        }


//      Проходим по массиву и если она заполнен, находим соответствующую категорию
//      если не заполнен, тогда заполянем пустыми значениями
        for ($i=0;$i<self::CATEGORY_COUNT;$i++) {
            if ((is_array($this->categories))and(key_exists($i,$this->categories))) {
                $this->categories[$i]['category']=Category::findOneForce($this->categories[$i]['category_id']);
            }  else {
                $this->categories[$i]=[
                    'category_id' => '',
                    'category' => null
                ];
            }
        }
    }

####
    public function save()
    {
        parent::save();
//        foreach ($this->categories as $i=>$category) {
//
//            /** @var Category $category */
//            if ($category=$this->categories[$i]['category']) {
//                $category->onShowWithoutGoods();
//                if (!$category->isOnSite()){
//                    $category->onSite();
//                }
//                $sites=$category->sites;
//                $findSite=false;
//                foreach ($sites as $site) {
//                    if ($site->isIdEqualTo()) {
//                        $findSite=false;
//                        break;
//                    }
//                }
//                if (!$findSite)
//                    $category->assignSite();
//
//                $category->save();
//                $this->categories[$i]['category'] = null;
//            }
//        }
    }
}