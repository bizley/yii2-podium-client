<?php

declare(strict_types=1);

namespace bizley\podium\client;

use bizley\podium\api\Podium;
use bizley\podium\client\base\Access;
use bizley\podium\client\base\Config;
use Yii;
use yii\base\Module;
use yii\bootstrap\Html;
use yii\console\Application;
use yii\helpers\Url;
use yii\i18n\PhpMessageSource;
use yii\twig\ViewRenderer;

/**
 * Class PodiumClient
 * @package bizley\podium\client
 */
class PodiumClient extends Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'main/index';

    /**
     * @var string
     */
    public $layout = 'default';

    /**
     * @var array|string|null
     */
    public $apiComponent;

    /**
     * @var array|string|null
     */
    public $configComponent;

    /**
     * @var array|string|null
     */
    public $accessComponent;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $this->setVersion('1.0.0');

        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'bizley\podium\client\commands';
        }

        $this->setPodiumApiComponent();
        $this->setPodiumConfigComponent();
        $this->setPodiumAccessComponent();

        $this->prepareTranslations();
        $this->prepareTwigRenderer();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setPodiumApiComponent(): void
    {
        $this->set('podiumApi', $this->apiComponent ?? ['class' => Podium::class]);
    }

    /**
     * @return null|object|Podium
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
     * @return null|object|Config
     * @throws \yii\base\InvalidConfigException
     */
    public function getPodiumConfig()
    {
        return $this->get('podiumConfig');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setPodiumAccessComponent(): void
    {
        $this->set('podiumAccess', $this->accessComponent ?? ['class' => Access::class]);
    }

    /**
     * @return null|object|Access
     * @throws \yii\base\InvalidConfigException
     */
    public function getPodiumAccess()
    {
        return $this->get('podiumAccess');
    }

    public function prepareTranslations(): void
    {
        Yii::$app->getI18n()->translations['podium.client.*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en',
            'forceTranslation' => true,
            'basePath' => __DIR__ . '/messages',
        ];
    }

    public function prepareTwigRenderer(): void
    {
        Yii::$app->getView()->renderers['twig'] = [
            'class' => ViewRenderer::class,
            'cachePath' => '@runtime/Twig/cache',
            'options' => ['auto_reload' => true],
            'globals' => [
                'Html' => ['class' => Html::class],
                'Url' => ['class' => Url::class],
                'Yii' => ['class' => Yii::class],
            ],
            'uses' => ['yii\bootstrap'],
        ];
    }
}
