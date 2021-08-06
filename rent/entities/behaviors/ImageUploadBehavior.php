<?php


namespace rent\entities\behaviors;


use PHPThumb\GD;
use yii\helpers\FileHelper;

class ImageUploadBehavior extends \yiidreamteam\upload\ImageUploadBehavior
{
    /**
     * @param string $attribute
     * @param string $profile
     * @param string|null $emptyUrl
     * @return string|null
     * @throws \yii\base\Exception
     */
    public function getThumbFileUrl($attribute, $profile = 'thumb', $emptyUrl = null)
    {
        if (!$this->owner->{$attribute}) {
            return $emptyUrl;
        }

        $behavior = static::getInstance($this->owner, $attribute);

        if ($behavior->createThumbsOnRequest) {
            $behavior->createThumbs();
        }

        return $behavior->resolveProfilePath($behavior->thumbUrl, $profile);
    }
    /**
     * Creates image thumbnails
     * @throws \yii\base\Exception
     */
    public function createThumbs()
    {
        $path = $this->getUploadedFilePath($this->attribute);
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = static::getThumbFilePath($this->attribute, $profile);
            if (is_file($path) && !is_file($thumbPath)) {

                // setup image processor function
                if (isset($config['processor']) && is_callable($config['processor'])) {
                    $processor = $config['processor'];
                    unset($config['processor']);
                } else {
                    $processor = function (GD $thumb) use ($config) {
                        $thumb->adaptiveResize($config['width'], $config['height']);
                    };
                }

                $thumb = new GD($path, $config);
                call_user_func($processor, $thumb, $this->attribute);
                FileHelper::createDirectory(pathinfo($thumbPath, PATHINFO_DIRNAME), 0775, true);

                //сохраняем в webp
                $ykv_parts=pathinfo($thumbPath);
                imagewebp($thumb->getWorkingImage(),$ykv_parts['dirname'].'/'.$ykv_parts['filename'].'.webp');

                $thumb->save($thumbPath);

            }
        }
    }
}