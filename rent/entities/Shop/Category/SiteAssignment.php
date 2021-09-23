<?php

namespace rent\entities\Shop\Category;

use yii\db\ActiveRecord;

/**
 * @property integer $product_id;
 * @property integer $site_id;
 */
class SiteAssignment extends ActiveRecord
{
    public static function create($siteId): self
    {
        $assignment = new static();
        $assignment->site_id = $siteId;
        return $assignment;
    }

    public function isForSite($id): bool
    {
        return $this->site_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%client_site_assignments_category}}';
    }
}