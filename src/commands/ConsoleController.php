<?php

declare(strict_types=1);

namespace bizley\podium\client\commands;

use bizley\podium\api\base\PodiumResponse;
use bizley\podium\api\Podium;
use bizley\podium\client\base\Access;
use bizley\podium\client\enums\Role;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Podium Setup.
 */
class ConsoleController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'setup';

    /**
     * @return Access
     */
    public function getAccess(): Access
    {
        return $this->module->getPodiumAccess();
    }

    /**
     * @return Podium
     */
    public function getApi(): Podium
    {
        return $this->module->getPodiumApi();
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action): bool // BC signature
    {
        if (parent::beforeAction($action)) {
            $this->stdout("\nPodium Console " . $this->module->version . "\n");
            $this->stdout(str_repeat('=', 70) . "\n");

            return true;
        }

        return false;
    }

    /**
     * Installs Podium module.
     * @return int
     * @throws \yii\base\NotSupportedException
     * @throws \Exception
     */
    public function actionSetup(): int
    {
        if (!$this->detectTables()) {
            return ExitCode::CONFIG;
        }

        if ($this->configureRbac()) {
            $this->configureAdmin();
        }

        return ExitCode::OK;
    }

    /**
     * @param string $text
     * @param string $char
     * @param int $length
     */
    public function renderLine(string $text, string $char = '.', int $length = 65): void
    {
        $size = $length - \strlen($text) - 1;
        if ($size < 0) {
            $size = 1;
        }
        $this->stdout($text . ' ' . str_repeat($char, $size) . ' ');
    }

    /**
     * @return bool
     * @throws \yii\base\NotSupportedException
     */
    public function detectTables(): bool
    {
        if (Yii::$app->db->getSchema()->getTableSchema('{{%podium_config}}') === null) {
            $this->stdout('>> ', Console::FG_RED);
            $this->stdout('ERROR', Console::FG_RED, Console::NEGATIVE);
            $this->stdout(": Podium database tables not found!\n", Console::FG_RED);
            $this->stdout('>> Please run "');
            $this->stdout('php yii migrate', Console::FG_YELLOW);
            $this->stdout("\" first.\n");

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function configureRbac(): bool
    {
        if ($this->confirm('> Would you like to set Podium permissions?')) {
            $this->renderLine('> Checking current permissions');

            if ($this->detectPermissions()) {
                $this->stdout("FOUND\n", Console::FG_YELLOW, Console::NEGATIVE);
                $this->stdout("> Please back up your database first before you continue with permissions purge.\n", Console::FG_YELLOW);

                if ($this->confirm('> Would you like to reset current Podium permissions (all members will lose their roles)?')) {
                    $this->renderLine('> Removing current permissions');

                    $this->getAccess()->removeAll();

                    $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
                } else {
                    $this->stdout(">> Current Podium permissions kept.\n", Console::FG_YELLOW);

                    return true;
                }
            } else {
                $this->stdout("EMPTY\n", Console::FG_GREEN, Console::NEGATIVE);
            }

            $this->renderLine('> Setting Podium permissions');

            if (!$this->savePermissions()) {
                $this->stdout("ERROR\n", Console::FG_RED, Console::NEGATIVE);

                return false;
            }

            $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
        } else {
            $this->stdout(">> Podium permissions setting skipped.\n");
        }

        return true;
    }

    /**
     * @return bool
     */
    public function detectPermissions(): bool
    {
        return !empty($this->getAccess()->getRoles());
    }

    /**
     * @return bool
     */
    public function savePermissions(): bool
    {
        return $this->getAccess()->setDefault();
    }

    /**
     * @throws \Exception
     */
    protected function configureAdmin(): void
    {
        $adminId = $this->prompt('> Enter database ID of user who should become Podium administrator (or just press enter to skip):');
        if ($adminId === '') {
            $this->stdout(">> Podium administrator has not been set.\n", Console::FG_YELLOW);
        } else {
            $this->renderLine("> Checking current member with ID \"{$adminId}\"");

            $member = $this->findMember($adminId);

            if ($member !== null) {
                $this->setExistingMemberAsAdmin($member, $adminId);
            } else {
                $this->setNewMemberAsAdmin($adminId);
            }
        }
    }

    /**
     * @param string $member
     * @param string $adminId
     * @throws \Exception
     */
    protected function setExistingMemberAsAdmin(string $member, string $adminId): void
    {
        $this->stdout("FOUND\n", Console::FG_YELLOW, Console::NEGATIVE);

        if ($this->confirm("> Would you like to make member \"{$member}\" the Podium Admin?")) {
            $this->renderLine("> Assigning Admin role for \"{$member}\"");

            $this->assignAdmin($adminId);

            Yii::warning("Assigning Podium Admin role for member with ID \"{$adminId}\" and username \"{$member}\".", 'podium');
            $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
        } else {
            $this->stdout(">> Podium administrator has not been set.\n", Console::FG_YELLOW);
        }
    }

    /**
     * @param string $adminId
     * @throws \Exception
     */
    protected function setNewMemberAsAdmin(string $adminId): void
    {
        while (true) {
            $username = $this->prompt('> Enter new administrator username (or just press enter to resign):');

            if ($username === '') {
                $this->stdout(">> Podium administrator has not been set.\n", Console::FG_YELLOW);
                break;
            }

            $this->renderLine("> Registering Podium member with ID \"{$adminId}\" and username \"{$username}\"");

            $registration = $this->registerMember($adminId, $username);

            if ($registration->result) {
                $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
                $this->renderLine("> Assigning Admin role for \"{$username}\"");

                $this->assignAdmin($adminId);

                Yii::warning("Registering new Podium administrator with ID \"{$adminId}\" and username \"{$username}\".", 'podium');
                $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
                break;
            }
            $this->stdout("ERROR\n", Console::FG_RED, Console::NEGATIVE);
            if (empty($registration->errors)) {
                Yii::error("Unknown error while registering new Podium administrator with ID \"{$adminId}\" and username \"{$username}\".", 'podium');
                break;
            }
            foreach ($registration->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stdout(">> {$error}\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * @param string $adminId
     * @param string $username
     * @return PodiumResponse
     */
    protected function registerMember(string $adminId, string $username): PodiumResponse
    {
        return $this->getApi()->member->register([
            'user_id' => $adminId,
            'username' => $username,
        ]);
    }

    /**
     * @param string $adminId
     * @throws \Exception
     */
    protected function assignAdmin(string $adminId): void
    {
        $this->getAccess()->revokeAll($adminId); // only one role per member
        $this->getAccess()->assign($this->getAccess()->getRole(Role::ADMIN), $adminId);
    }

    /**
     * @param string $adminId
     * @return null|string
     */
    protected function findMember(string $adminId): ?string
    {
        $member = $this->getApi()->member->getMemberByUserId($adminId);
        return $member !== null ? $member->username : null;
    }
}
