<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%file}}`.
 */
class m200524_182748_add_columns_to_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//      Ширина и высота изображений
        $this->addColumn('{{%file}}', 'width', $this->integer()->unsigned());
        $this->addColumn('{{%file}}', 'height', $this->integer()->unsigned());

//      Иконка ввида "glyphicon glyphicon-list-alt"
        $this->addColumn('{{%category}}','icon', $this->string(100));
//      идентификатор файла избражения, для миниатюры категории
        $this->addColumn('{{%category}}','thumbnail_id', $this->integer()->unsigned());

        $this->addForeignKey(
            'fk-category-thumbnail_id',
            '{{%category}}',
            'thumbnail_id',
            '{{%file}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-category-thumbnail_id','{{%category}}');
        $this->dropColumn('{{%file}}', 'width');
        $this->dropColumn('{{%file}}', 'height');
        $this->dropColumn('{{%category}}', 'icon');
        $this->dropColumn('{{%category}}', 'thumbnail_id');

    }
}
