<?php

namespace rent\entities\Shop\Product;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use rent\services\WaterMarker;

/**
 * @property integer $id
 * @property string $file
 * @property integer $sort
 * @property integer $product_id
 *
 * @mixin ImageUploadBehavior
 */
class Photo extends ActiveRecord
{
    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName(): string
    {
        return '{{%shop_photos}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/products/[[id_path]]/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/products/[[id_path]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/products/[[id_path]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/products/[[id_path]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    '270x270' => ['width' => 270, 'height' => 270],
                    '1920x800' => ['width' => 1920, 'height' => 800],
                    '1171x300' => ['width' => 1171, 'height' => 300],
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'backend_thumb' => ['width' => 500, 'height' => 500],
                    'cart_list' => ['width' => 150, 'height' => 150],
                    'cart_widget_list' => ['width' => 57, 'height' => 57],
                    'catalog_list' => ['width' => 340, 'height' => 510],
//                    'catalog_product_main' => ['processor' => [new WaterMarker(750, 1000, '@frontend/web/image/logo.png'), 'process']],
                    'catalog_product_additional' => ['width' => 66, 'height' => 66],
                    'catalog_product' => ['width' => 440, 'height' => 590],
//                    'catalog_origin' => ['processor' => [new WaterMarker(1024, 768, '@frontend/web/image/logo.png'), 'process']],

                ],
            ],
        ];
    }

    /**
     * Replaces all placeholders in path variable with corresponding values
     *
     * @param string $path
     * @return string
     */
    public function resolvePath($path)
    {
//        $path = $this->resolvePath($path);
        return $path;

    }
}