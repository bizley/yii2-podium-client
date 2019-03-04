<?php

declare(strict_types=1);

namespace bizley\podium\client;

use bizley\podium\api\Podium;
use bizley\podium\client\base\Access;
use bizley\podium\client\base\Notify;
use bizley\podium\client\base\Config;
use bizley\podium\client\widgets\fontawesome\FA;
use Yii;
use yii\base\Module;
use yii\bootstrap4\Html;
use yii\console\Application;
use yii\helpers\Url;
use yii\i18n\PhpMessageSource;
use yii\twig\ViewRenderer;

/**
 * Class PodiumClient
 * @package bizley\podium\client
 *
 * @property Podium $api
 * @property Access $access
 * @property Notify $notify
 * @property Config $config
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
    public $apiConfig;

    /**
     * @var array|string|null
     */
    public $configConfig;

    /**
     * @var array|string|null
     */
    public $accessConfig;

    /**
     * @var array|string|null
     */
    public $notifyConfig;

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

        $this->setApi();
        $this->setConfig();
        $this->setAccess();
        $this->setNotify();

        $this->prepareTranslations();
        $this->prepareTwigRenderer();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setApi(): void
    {
        $this->set('api', $this->apiConfig ?? ['class' => Podium::class]);
    }

    /**
     * @return null|object|Podium
     * @throws \yii\base\InvalidConfigException
     */
    public function getApi()
    {
        return $this->get('api');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setConfig(): void
    {
        $this->set('config', $this->configConfig ?? ['class' => Config::class]);
    }

    /**
     * @return null|object|Config
     * @throws \yii\base\InvalidConfigException
     */
    public function getConfig()
    {
        return $this->get('config');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setAccess(): void
    {
        $this->set('access', $this->accessConfig ?? ['class' => Access::class]);
    }

    /**
     * @return null|object|Access
     * @throws \yii\base\InvalidConfigException
     */
    public function getAccess()
    {
        return $this->get('access');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setNotify(): void
    {
        $this->set('notify', $this->notifyConfig ?? ['class' => Notify::class]);
    }

    /**
     * @return null|object|Notify
     * @throws \yii\base\InvalidConfigException
     */
    public function getAlert()
    {
        return $this->get('notify');
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
            'uses' => ['yii\bootstrap4'],
            'functions' => [
                'FA' => [
                    [FA::class, 'icon'],
                    ['is_safe' => ['html']]
                ],
            ],
        ];
    }
}
