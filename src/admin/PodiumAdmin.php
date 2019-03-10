<?php

declare(strict_types=1);

namespace bizley\podium\client\admin;

use bizley\podium\api\Podium;
use bizley\podium\client\base\Access;
use bizley\podium\client\base\Config;
use bizley\podium\client\base\Notify;
use Yii;
use yii\base\Module;
use yii\console\Application;
use yii\i18n\PhpMessageSource;

/**
 * Class PodiumAdmin
 * @package bizley\podium\client\admin
 *
 * @property Podium $api
 * @property Access $access
 * @property Notify $notify
 * @property Config $config
 */
class PodiumAdmin extends Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'main/index';

    /**
     * @var string
     */
    public $layout = 'admin.twig';

    public function init(): void
    {
        parent::init();

        $this->setVersion('1.0.0');

        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'bizley\podium\client\admin\commands';
        }

        $this->prepareTranslations();
    }

    /**
     * @return null|object|Podium
     * @throws \yii\base\InvalidConfigException
     */
    public function getApi()
    {
        return $this->module->get('api');
    }

    /**
     * @return null|object|Config
     * @throws \yii\base\InvalidConfigException
     */
    public function getConfig()
    {
        return $this->module->get('config');
    }

    /**
     * @return null|object|Access
     * @throws \yii\base\InvalidConfigException
     */
    public function getAccess()
    {
        return $this->module->get('access');
    }

    /**
     * @return null|object|Notify
     * @throws \yii\base\InvalidConfigException
     */
    public function getAlert()
    {
        return $this->module->get('notify');
    }

    public function prepareTranslations(): void
    {
        Yii::$app->getI18n()->translations['podium.admin.*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en',
            'forceTranslation' => true,
            'basePath' => __DIR__ . '/messages',
        ];
    }

    /**
     * @return string
     */
    public function getPodiumId(): string
    {
        return $this->module->id;
    }

    /**
     * @return string
     */
    public function getPodiumVersion(): string
    {
        return $this->module->version;
    }

    /**
     * @return string
     */
    public function getPodiumAdminId(): string
    {
        return $this->id;
    }
}
