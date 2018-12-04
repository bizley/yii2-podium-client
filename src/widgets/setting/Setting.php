<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\setting;

use bizley\podium\client\base\Config;
use bizley\podium\client\enums\Setting as SettingEnum;
use yii\base\DynamicModel;
use yii\base\Widget;

/**
 * Class Setting
 * @package bizley\podium\client\widgets\setting
 */
class Setting extends Widget
{
    /**
     * @var string
     */
    public $data;

    /**
     * @var string
     */
    public $type;

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->getView()->context->module->getPodiumConfig();
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $model = new DynamicModel([
            'description' => SettingEnum::get($this->data),
            $this->data => $this->getConfig()->getValue($this->data),
        ]);

        return $this->render('view.twig', [
            'type' => $this->type,
            'model' => $model,
            'name' => $this->data,
        ]);
    }
}
