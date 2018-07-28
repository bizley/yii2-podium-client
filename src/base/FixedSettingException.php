<?php

declare(strict_types=1);

namespace bizley\podium\client\base;

use yii\base\Exception;

/**
 * Class FixedSettingException
 * @package bizley\podium\client\base
 */
class FixedSettingException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Fixed Setting Exception';
    }
}
