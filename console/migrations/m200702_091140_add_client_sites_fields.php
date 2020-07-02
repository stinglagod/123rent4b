<?php

use yii\db\Migration;

/**
 * Class m200702_091140_add_client_sites_fields
 */
class m200702_091140_add_client_sites_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'email', $this->string());
        $this->addColumn('{{%client_sites}}', 'urlInstagram', $this->string());
        $this->addColumn('{{%client_sites}}', 'urlTwitter', $this->string());
        $this->addColumn('{{%client_sites}}', 'urlFacebook', $this->string());
        $this->addColumn('{{%client_sites}}', 'urlGooglePlus', $this->string());
        $this->addColumn('{{%client_sites}}', 'urlVk', $this->string());
        $this->addColumn('{{%client_sites}}', 'urlOk', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_sites}}', 'email');
        $this->dropColumn('{{%client_sites}}', 'urlInstagram');
        $this->dropColumn('{{%client_sites}}', 'urlTwitter');
        $this->dropColumn('{{%client_sites}}', 'urlFacebook');
        $this->dropColumn('{{%client_sites}}', 'urlGooglePlus');
        $this->dropColumn('{{%client_sites}}', 'urlVk');
        $this->dropColumn('{{%client_sites}}', 'urlOk');

    }
}
