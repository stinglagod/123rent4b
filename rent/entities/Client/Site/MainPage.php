<?php
namespace rent\entities\Client\Site;

use rent\entities\abstracts\JsonAbstract;
use rent\entities\Client\File;
use rent\entities\Shop\Category\Category;
use rent\forms\manage\Client\Site\MainPageForm;
use yii\helpers\Json;

class MainPage extends JsonAbstract
{
    public $mainSlider;
    public $banners;
    public $categories;

    public function __construct(string $json=null,MainPageForm $mainPageForm=null)
    {
        $this->mainSlider=[];
        $this->banners=[];
        $this->categories=[];

        if ($json) {

            $this->set(Json::decode($json));
            // находим image по id
            foreach ($this->mainSlider as $i=>$slider) {
                if ($slider['image_id']) {
                    $this->mainSlider[$i]['image']=File::findOne($slider['image_id']);

                }
            }

            if (is_array($this->banners)) {
                foreach ($this->banners as $i => $banner) {

                    if (isset($banner['image_id'])) {
                        $this->banners[$i]['image'] = File::findOne($banner['image_id']);
                    }
                }
            }

            if (is_array($this->categories)) {
                foreach ($this->categories as $i => $category) {
                    if (isset($category['category_id'])) {
                        $this->categories[$i]['category'] = Category::findOneForce($category['category_id']);
                    }
                }
            }
        } else if ($mainPageForm) {
            foreach ($mainPageForm->mainSliders as $mainSlider) {
                $this->mainSlider[]=[
                    'image'=>$mainSlider->image,
                    'text'=>$mainSlider->text,
                    'text2'=>$mainSlider->text2,
                    'url'=>$mainSlider->url,
                    'urlText'=>$mainSlider->urlText
                ];
            }
            foreach ($mainPageForm->banners as $banner) {
                $this->banners[]=[
                    'image'=>$banner->image,
                    'name'=>$banner->name,
                    'url'=>$banner->url,
                ];
            }
            foreach ($mainPageForm->categories as $category) {
                $this->categories[]=[
                    'category_id'=>$category->category_id,
                    'category'=>$category->category,
                ];
            }
        }

        for ($i = 0; $i < 3; $i++) {
            if ((is_array($this->banners))and(!key_exists($i,$this->banners))) {
                $this->banners[$i]=[
                    'image_id'=>'',
                    'image'=>'',
                    'name'=>'',
                    'url'=>'',
                ];
            }
        }
        if (empty($this->categories)) {
            for ($i = 0; $i < 3; $i++) {
                $this->categories[$i]=[
                    'category'=>'',
                    'category_id'=>'',
                ];
            }
        }

        $this->mainSlider[]=[
            'image'=>'',
            'image_id'=>'',
            'text'=>'',
            'text2'=>'',
            'url'=>'',
            'urlText'=>''
        ];

    }

//    public function set($data) {
//        foreach ($data AS $key => $value) {
//            if (isset($this->{$key})) {
//                $this->{'old'.$key} = $this->{$key};
//            }
//            $this->{$key} = $value;
//        }
//    }
    public function save()
    {
        parent::save();
###MainSlider
        $num=0;
        $mainSlider=[];
        foreach ($this->mainSlider as $i=>$slider) {
            if ($slider['image'] or
                $slider['text'] or
                $slider['url']) {

                if (is_object($slider['image'])) {
                    if ($slider['image']->save()) {
                        $this->mainSlider[$i]['image_id']=$slider['image']->id;
                    } else {
                        throw new \DomainException('Ошибка при сохранение слайдера');
                    }
                }
                $this->mainSlider[$i]['image']=null;

                $mainSlider[$num]=$this->mainSlider[$i];
                $num++;
            } else {
                unset($this->mainSlider[$i]);
            }

        }
        $this->mainSlider=$mainSlider;
//        удаляем старое изображение. Что бы не засорять
        if (isset($this->oldmainSlider)) {
            foreach ( $this->oldmainSlider as $oldSlider) {
                $found=false;
                foreach ($this->mainSlider as $slider) {
                    if ($oldSlider['image_id']==$slider['image_id']) {
                        $found=true;
                        break;
                    }
                }
                if ($found==false) {
                    if ($oldImage=File::findOne($oldSlider['image_id'])) {
                        $oldImage->delete();
                    }
                }
            }
        }

###Banners
        $num=0;
        $banners=[];
        foreach ($this->banners as $i=>$banner) {
            if ($banner['image'] or
                $banner['name'] or
                $banner['url']) {

                if (is_object($banner['image'])) {
                    if ($banner['image']->save()) {
                        $this->banners[$i]['image_id']=$banner['image']->id;
                    } else {
                        throw new \DomainException('Ошибка при сохранение слайдера');
                    }
                }
                $this->banners[$i]['image']=null;

                $banners[$num]=$this->banners[$i];
                $num++;
            } else {
                unset($this->banners[$i]);
            }
        }
//        удаляем старое изображение. Что бы не засорять
        if (isset($this->oldbanners)) {
            foreach ( $this->oldbanners as $oldBanner) {
                $found=false;
                foreach ($this->banners as $banner) {
                    if ($oldBanner['image_id']==$banner['image_id']) {
                        $found=true;
                        break;
                    }
                }
                if ($found==false) {
                    if ($oldImage=File::findOne($oldBanner['image_id'])) {
                        $oldImage->delete();
                    }
                }
            }
        }
###Category
        foreach ($this->categories as $i=>$category) {
            $this->categories[$i]['category']=null;
        }
###Other
        //очищаем от old аттрибутов
        foreach ($this as $key=>$value) {
            if (strripos($key,'old')===false) { continue;}
            unset($this->{$key});
        }
//        var_dump($this);exit;

    }
//    public function getJson()
//    {
//        $this->save();
//        return Json::encode($this);
//    }

### MainSlider
    public function mainSliderUp ($key)
    {
        foreach ($this->mainSlider as $i=>$slider) {
            if ($i==$key) {
                if ($prev=$this->mainSlider[$i-1]??null) {
                    $this->mainSlider[$i-1]=$slider;
                    $this->mainSlider[$i]=$prev;
                }
                return;
            }
        }
    }
    public function mainSliderDown ($key)
    {
        foreach ($this->mainSlider as $i=>$slider) {
            if ($i==$key) {
                if ($next=$this->mainSlider[$i+1]??null) {
                    $this->mainSlider[$i]=$next;
                    $this->mainSlider[$i+1]=$slider;
                }
                return;
            }
        }
    }
    public function removeMainSlider ($key)
    {
        foreach ($this->mainSlider as $i=>$slider) {
            if ($i == $key) {
                $this->mainSlider[$key]['image']->delete();
                unset($this->mainSlider[$key]);
            }
        }
    }
}