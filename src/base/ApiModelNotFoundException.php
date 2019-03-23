<?php

declare(strict_types=1);

namespace bizley\podium\client\base;

use yii\base\Exception;

/**
 * Class ApiModelNotFoundException
 * @package bizley\podium\client\base
 */
class ApiModelNotFoundException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'API Model Not Found Exception';
    }
}
