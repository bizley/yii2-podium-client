<?php

declare(strict_types=1);

namespace bizley\podium\client\filters;

use bizley\podium\api\Podium;
use bizley\podium\client\PodiumClient;
use Closure;
use yii\base\InvalidConfigException;
use yii\web\User;

/**
 * Class PodiumAccessRule
 * @package bizley\podium\client\filters
 */
class PodiumAccessRule extends \yii\filters\AccessRule
{
    /**
     * @var PodiumClient|array|string Podium client module.
     */
    public $podium;

    /**
     * @param User $user the user object
     * @return bool whether the rule applies to the role
     * @throws InvalidConfigException if User component is detached
     */
    protected function matchRole($user): bool // BC signature
    {
        $items = empty($this->roles) ? [] : $this->roles;

        if (!empty($this->permissions)) {
            $items = array_merge($items, $this->permissions);
        }

        if (empty($items)) {
            return true;
        }

        if ($user === false) {
            throw new InvalidConfigException('The user application component must be available to specify roles in AccessRule.');
        }

        foreach ($items as $item) {
            if ($item === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($item === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
            } else {
                if (!isset($roleParams)) {
                    $roleParams = $this->roleParams instanceof Closure ? call_user_func($this->roleParams, $this) : $this->roleParams;
                }

                if ($user->getId() !== null) {
                    $member = $this->podium->getPodiumApi()->getMember()->getMemberByUserId($user->getId());
                    if ($member && $this->podium->getPodiumAccess()->check($member, $item, $roleParams)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
