<?php

declare(strict_types=1);

namespace bizley\podium\client\events;

use yii\base\Event;

/**
 * Class RbacEvent
 * @package bizley\podium\client\events
 */
class RbacEvent extends Event
{
    /**
     * @var bool whether default RBAC configuration can be set
     */
    public $canSetup = true;
}
