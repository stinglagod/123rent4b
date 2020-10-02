<?php
namespace rent\entities\Client\Site;

use common\models\Product;
use rent\entities\Client\File;
use rent\forms\manage\Client\Site\MainPageForm;
use yii\helpers\Json;

class MainPage
{
    public $mainSlider;
    public $banner1;
    public $category1;
    public $banner2;
    public $category2;
    public $banner3;
    public $category3;

    public function __construct(string $json=null,MainPageForm $mainPageForm=null)
    {
        if ($json) {
            $this->set(Json::decode($json));
            // находим image по id
            foreach ($this->mainSlider as $i=>$slider) {
                if ($slider['image_id']) {
                    $this->mainSlider[$i]['image']=File::findOne($slider['image_id']);
                }
            }
        }
        if ($mainPageForm) {
            foreach ($mainPageForm->mainSliders as $mainSlider) {
                $this->mainSlider[]=[
                    'image'=>$mainSlider->image,
                    'text'=>$mainSlider->text,
                    'text2'=>$mainSlider->text2,
                    'url'=>$mainSlider->url,
                    'urlText'=>$mainSlider->urlText
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

    public function set($data) {
        foreach ($data AS $key => $value) {
            $this->{'old'.$key} = $this->{$key};
            $this->{$key} = $value;
        }
    }
    public function save()
    {
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
    public function getJson()
    {
        $this->save();
        return Json::encode($this);
    }

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