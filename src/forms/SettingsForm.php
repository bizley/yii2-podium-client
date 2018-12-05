<?php

declare(strict_types=1);

namespace bizley\podium\client\forms;

use bizley\podium\client\enums\Setting;
use bizley\podium\client\interfaces\ConfigInterface;
use yii\base\Model;

/**
 * Class SettingsForm
 * @package bizley\podium\client\forms
 */
class SettingsForm extends Model
{
    /**
     * @var int
     */
    public $polls_allowed;

    /**
     * @var int
     */
    public $min_posts_for_hot;

    /**
     * @var int
     */
    public $members_visible;

    /**
     * @var int
     */
    public $merge_posts;

    /**
     * @var string
     */
    public $name;

    private $configComponent;

    public function __construct(ConfigInterface $configComponent, array $config = [])
    {
        $this->configComponent = $configComponent;

        parent::__construct($config);
    }

    public function init(): void
    {
        parent::init();

        $this->name = $this->configComponent->getValue(Setting::NAME);
        $this->members_visible = $this->configComponent->getValue(Setting::MEMBERS_VISIBLE);
        $this->merge_posts = $this->configComponent->getValue(Setting::MERGE_POSTS);
        $this->min_posts_for_hot = $this->configComponent->getValue(Setting::MIN_POSTS_FOR_HOT);
        $this->polls_allowed = $this->configComponent->getValue(Setting::POLLS_ALLOWED);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['polls_allowed', 'members_visible', 'merge_posts'], 'boolean', 'skipOnEmpty' => false],
            [['min_posts_for_hot'], 'integer', 'min' => 0, 'skipOnEmpty' => false],
            [['name'], 'string', 'min' => 1, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return Setting::data();
    }

    /**
     * @return array
     */
    public function panels(): array
    {
        return [
            Setting::NAME => 'text',
            Setting::MEMBERS_VISIBLE => 'radio',
            Setting::MERGE_POSTS => 'radio',
            Setting::MIN_POSTS_FOR_HOT => 'text',
            Setting::POLLS_ALLOWED => 'radio',
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $fields = array_keys($this->panels());

        foreach ($fields as $field) {
            if (!$this->configComponent->setValue($field, $this->$field)) {
                return false;
            }
        }

        return true;
    }
}
