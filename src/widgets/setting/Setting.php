<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\setting;

use bizley\podium\client\forms\SettingsForm;
use yii\base\Widget;

/**
 * Class Setting
 * @package bizley\podium\client\widgets\setting
 */
class Setting extends Widget
{
    /**
     * @var SettingsForm
     */
    public $model;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render('view.twig', [
            'type' => $this->type,
            'model' => $this->model,
            'name' => $this->name,
        ]);
    }
}
