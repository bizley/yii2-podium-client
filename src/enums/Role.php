<?php

declare(strict_types=1);

namespace bizley\podium\client\enums;

use bizley\podium\api\enums\BaseEnum;
use Yii;

/**
 * Class Role
 * @package bizley\podium\client\enums
 */
final class Role extends BaseEnum
{
    public const GUEST = 'guest';
    public const MEMBER = 'member';
    public const MODERATOR = 'moderator';
    public const ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function data(): array
    {
        return [
            self::GUEST => Yii::t('podium.client.enum', 'role.guest'),
            self::MEMBER => Yii::t('podium.client.enum', 'role.member'),
            self::MODERATOR => Yii::t('podium.client.enum', 'role.moderator'),
            self::ADMIN => Yii::t('podium.client.enum', 'role.admin'),
        ];
    }
}
