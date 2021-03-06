<?php

declare(strict_types=1);

namespace bizley\podium\client\migrations;

use yii\db\Migration;

class m180721_173600_create_table_podium_config extends Migration
{
    public function up(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%podium_config}}', [
            'param' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-podium_config', '{{%podium_config}}', 'param');
    }

    public function down(): void
    {
        $this->dropTable('{{%podium_config}}');
    }
}
