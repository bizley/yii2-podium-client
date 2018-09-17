<?php

declare(strict_types=1);

namespace bizley\podium\client\base;

use bizley\podium\api\interfaces\MembershipInterface;
use bizley\podium\client\rbac\RbacSetup;
use yii\rbac\DbManager;

/**
 * Class Access
 * @package bizley\podium\client\base
 */
class Access extends DbManager
{
    /**
     * @var string the name of the table storing authorization items. Defaults to "podium_auth_item".
     */
    public $itemTable = '{{%podium_auth_item}}';

    /**
     * @var string the name of the table storing authorization item hierarchy. Defaults to "podium_auth_item_child".
     */
    public $itemChildTable = '{{%podium_auth_item_child}}';

    /**
     * @var string the name of the table storing authorization item assignments. Defaults to "podium_auth_assignment".
     */
    public $assignmentTable = '{{%podium_auth_assignment}}';

    /**
     * @var string the name of the table storing rules. Defaults to "podium_auth_rule".
     */
    public $ruleTable = '{{%podium_auth_rule}}';

    private $_access = [];

    /**
     * @param MembershipInterface $member
     * @param string $permissionName
     * @param array $params
     * @return bool
     */
    public function check(MembershipInterface $member, string $permissionName, array $params = []): bool
    {
        if (empty($params) && isset($this->_access[$permissionName])) {
            return $this->_access[$permissionName];
        }
        $access = $this->checkAccess($member->getId(), $permissionName, $params);
        if (empty($params)) {
            $this->_access[$permissionName] = $access;
        }

        return $access;
    }

    /**
     * Creates default roles with default permissions.
     * @return bool
     */
    public function setDefault(): bool
    {
        return (new RbacSetup($this))->run();
    }
}
