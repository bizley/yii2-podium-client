<?php

declare(strict_types=1);

namespace bizley\podium\client\interfaces;

use bizley\podium\api\interfaces\MembershipInterface;
use yii\rbac\DbManager;
use yii\rbac\Permission;
use yii\rbac\Role;

/**
 * Interface AssigningInterface
 * @package bizley\podium\client\interfaces
 */
interface AssigningInterface
{
    /**
     * @param DbManager $manager
     */
    public function setManager(DbManager $manager): void;

    /**
     * @param MembershipInterface $member
     */
    public function setMember(MembershipInterface $member): void;

    /**
     * @param Role|Permission $role
     */
    public function setRole($role): void;

    /**
     * Switches current member role to new one.
     * @return bool
     */
    public function switch(): bool;
}
