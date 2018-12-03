<?php

declare(strict_types=1);

namespace bizley\podium\client\filters;

use bizley\podium\client\PodiumClient;
use yii\di\Instance;

/**
 * Class PodiumAccessControl
 * @package bizley\podium\client\filters
 */
class PodiumAccessControl extends \yii\filters\AccessControl
{
    /**
     * @var PodiumClient|array|string Podium client module.
     */
    public $podium;

    /**
     * @var array the default configuration of access rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = ['class' => PodiumAccessRule::class];

    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->podium = Instance::ensure($this->podium, PodiumClient::class);
        $this->ruleConfig['podium'] = $this->podium;
    }
}
