<?php

declare(strict_types=1);

namespace bizley\podium\client\repos;

use yii\db\ActiveRecord;

/**
 * Config Active Record.
 *
 * @property string $param
 * @property string $value
 */
class ConfigRepo extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%podium_config}}';
    }
}
