<?php

declare(strict_types=1);

namespace bizley\podium\client;

use bizley\podium\api\Podium;
use yii\base\Module;
use yii\console\Application;

/**
 * Class PodiumClient
 * @package bizley\podium\client
 */
class PodiumClient extends Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'main';

    /**
     * @var string
     */
    public $layout = 'main';

    /**
     * @var array|null
     */
    public $api;

    public function init()
    {
        parent::init();

        if ($this->api === null) {
            $this->set('api', [
                'class' => Podium::class
            ]);
        }

        $this->setVersion('1.0.0');

        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'bizley\podium\client\commands';
        }
    }
}
