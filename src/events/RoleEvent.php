<?php

declare(strict_types=1);

namespace bizley\podium\client\events;

use bizley\podium\client\rbac\Assigning;
use yii\base\Event;

/**
 * Class RoleEvent
 * @package bizley\podium\client\events
 */
class RoleEvent extends Event
{
    /**
     * @var bool whether role can be assigned
     */
    public $canAssign = true;

    /**
     * @var Assigning
     */
    public $model;
}
