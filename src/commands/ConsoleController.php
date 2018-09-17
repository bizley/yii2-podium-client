<?php

declare(strict_types=1);

namespace bizley\podium\client\commands;

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
    public function renderLine(string $text, string $char = '.', int $length = 60): void
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
                } else {
                    $this->stdout(">> Current Podium permissions kept.\n", Console::FG_YELLOW);
                }
            } else {
                $this->stdout("EMPTY\n", Console::FG_GREEN, Console::NEGATIVE);
            }
            $this->renderLine('> Setting Podium permissions');
            if ($this->savePermissions()) {
                $this->stdout("ERROR\n", Console::FG_RED, Console::NEGATIVE);
                return false;
            }
            $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
        } else {
            $this->stdout(">> Podium permissions setting skipped.\n");
        }
        return true;
    }

    protected function configureAdmin(): void
    {
        $adminId = $this->prompt('> Enter database ID of user who should become Podium administrator (enter to skip):');
        if ($adminId === '') {
            $this->stdout(">> Podium administrator has not been set.\n", Console::FG_YELLOW);
        } else {
            Yii::warning("Setting new Podium administrator with ID \"{$adminId}\".", 'podium');
            $this->renderLine("> Setting Podium administrator with ID \"{$adminId}\"");
            if (!$this->saveAdmin($adminId)) {
                $this->stdout("ERROR\n", Console::FG_RED, Console::NEGATIVE);
            } else {
                $this->stdout("DONE\n", Console::FG_GREEN, Console::NEGATIVE);
            }
        }
    }

    protected function saveAdmin(string $adminId): bool
    {
        return true;
    }
}
