<?php

declare(strict_types=1);

namespace bizley\podium\client\interfaces;

/**
 * Interface ConfigInterface
 * @package bizley\podium\client\interfaces
 */
interface ConfigInterface
{
    /**
     * @param string $param
     * @param string $value
     * @return bool
     */
    public function setValue(string $param, string $value): bool;

    /**
     * @param string $param
     * @param string|null $default
     * @return string|null
     */
    public function getValue(string $param, ?string $default = null): ?string;
}
