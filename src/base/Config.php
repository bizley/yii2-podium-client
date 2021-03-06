<?php

declare(strict_types=1);

namespace bizley\podium\client\base;

use bizley\podium\client\enums\Setting;
use bizley\podium\client\interfaces\ConfigInterface;
use bizley\podium\client\repos\ConfigRepo;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Config
 * @package bizley\podium\client\base
 *
 * @property array $defaultValues
 */
class Config extends Component implements ConfigInterface
{
    /**
     * @var array
     */
    public $settings = [];

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (!\is_array($this->settings)) {
            throw new InvalidConfigException('Settings property must be an array');
        }
    }

    /**
     * @return array
     */
    public function getDefaultValues(): array
    {
        return [
            Setting::NAME => 'Podium',
            Setting::MEMBERS_VISIBLE => '1',
            Setting::POLLS_ALLOWED => '1',
            Setting::MIN_POSTS_FOR_HOT => '20',
            Setting::MERGE_POSTS => '1',
        ];
    }

    private $_config = [];

    /**
     * @param string $param
     * @param string $value
     * @return bool
     * @throws FixedSettingException
     */
    public function setValue(string $param, string $value): bool
    {
        if (array_key_exists($param, $this->settings)) {
            throw new FixedSettingException("Fixed configuration prevents changing '{$param}' parameter");
        }

        if ($this->storeValue($param, $value)) {
            $this->_config[$param] = $value;

            return true;
        }

        return false;
    }

    /**
     * @param string $param
     * @return string|null
     */
    public function getValue(string $param): ?string
    {
        $defaultValues = $this->getDefaultValues();

        return $this->getRawValue($param, $defaultValues[$param] ?? null, true);
    }

    /**
     * @param string $param
     * @param string|null $default
     * @param bool $cached
     * @return string|null
     */
    public function getRawValue(string $param, ?string $default = null, bool $cached = true): ?string
    {
        if ($cached && array_key_exists($param, $this->_config)) {
            return $this->_config[$param];
        }

        if (array_key_exists($param, $this->settings)) {
            return (string) $this->settings[$param];
        }

        return $this->retrieveValue($param, $default);
    }

    /**
     * @param string $param
     * @param string $value
     * @return bool
     */
    protected function storeValue(string $param, string $value): bool
    {
        $setting = ConfigRepo::findOne(['param' => $param]);

        if ($setting === null) {
            $setting = new ConfigRepo(['param' => $param]);
        }

        $setting->value = $value;

        if (!$setting->save()) {
            Yii::error(['config.store.value.error', $setting->errors], 'podium');

            return false;
        }

        return true;
    }

    /**
     * @param string $param
     * @param string|null $default
     * @return null|string
     */
    protected function retrieveValue(string $param, ?string $default = null): ?string
    {
        $setting = ConfigRepo::findOne(['param' => $param]);

        return $setting !== null ? $setting->value : $default;
    }
}
