<?php

declare(strict_types=1);

namespace bizley\podium\client;

use bizley\podium\api\Podium;
use bizley\podium\client\base\Config;
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
     * @var array|string|null
     */
    public $apiComponent;

    /**
     * @var array|string|null
     */
    public $configComponent;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $this->setVersion('1.0.0');

        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'bizley\podium\client\commands';
        }

        $this->setPodiumApiComponent();
        $this->setPodiumConfigComponent();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setPodiumApiComponent(): void
    {
        $this->set('podiumApi', $this->apiComponent ?? ['class' => Podium::class]);
    }

    /**
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public function getPodiumApi()
    {
        return $this->get('podiumApi');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setPodiumConfigComponent(): void
    {
        $this->set('podiumConfig', $this->configComponent ?? ['class' => Config::class]);
    }

    /**
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public function getPodiumConfig()
    {
        return $this->get('podiumConfig');
    }
}
