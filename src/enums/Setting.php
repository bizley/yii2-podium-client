<?php

declare(strict_types=1);

namespace bizley\podium\client\enums;

use bizley\podium\api\enums\BaseEnum;
use Yii;

/**
 * Class Setting
 * @package bizley\podium\client\enums
 */
final class Setting extends BaseEnum
{
    public const POLLS_ALLOWED = 'polls_allowed';
    public const MIN_POSTS_FOR_HOT = 'min_posts_for_hot';
    public const MEMBERS_VISIBLE = 'members_visible';
    public const MERGE_POSTS = 'merge_posts';
    public const NAME = 'name';

    /**
     * {@inheritdoc}
     */
    public static function data(): array
    {
        return [
            self::POLLS_ALLOWED => Yii::t('podium.client.enum', 'setting.polls.allowed'),
            self::MIN_POSTS_FOR_HOT => Yii::t('podium.client.enum', 'setting.minimum.posts.for.hot'),
            self::MEMBERS_VISIBLE => Yii::t('podium.client.enum', 'setting.members.visible'),
            self::MERGE_POSTS => Yii::t('podium.client.enum', 'setting.merge.posts'),
            self::NAME => Yii::t('podium.client.enum', 'setting.podium.name'),
        ];
    }
}
