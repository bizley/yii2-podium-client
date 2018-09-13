<?php

declare(strict_types=1);

namespace bizley\podium\client\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Podium Installer.
 */
class InstallController extends Controller
{
    /**
     * Installs Podium module.
     * @return int
     */
    public function actionIndex(): int
    {
        //var_dump($this->module);die;

        $this->stdout("\nPodium Installer " . $this->module->version . "\n");
        $this->stdout(str_repeat('=', 60) . "\n");

        if (!$this->detectTables()) {
            return ExitCode::CONFIG;
        }

        if (!$this->checkVersion()) {
            return ExitCode::CONFIG;
        }

        return ExitCode::OK;
    }

    public function renderLine($text, $char = '.', $length = 30): void
    {
        $this->stdout($text . ' ' . str_repeat($char, $length - \strlen($text) - 1) . ' ');
    }

    public function detectTables(): bool
    {
        $this->renderLine('Podium database');
        if (Yii::$app->db->getSchema()->getTableSchema('{{%podium_config}}') === null) {
            $this->stdout('ERROR', Console::FG_RED, Console::NEGATIVE);
            $this->stdout(" Podium database tables not found!\n");
            $this->stdout('Please run "');
            $this->stdout('php yii migrate', Console::FG_YELLOW);
            $this->stdout("\" first.\n");

            return false;
        }
        $this->stdout("OK\n", Console::FG_GREEN, Console::NEGATIVE);
        return true;
    }

    public function checkVersion(): bool
    {
        $this->renderLine('Checking version');
//        if (Yii::$app->db->getSchema()->getTableSchema('{{%podium_config}}') === null) {
//            $this->stdout('! ERROR !', Console::FG_RED, Console::NEGATIVE);
//            $this->stdout(" Podium database tables not found!\n");
//            $this->stdout('Please run "');
//            $this->stdout('php yii migrate', Console::FG_YELLOW);
//            $this->stdout("\" first.\n");
//
//            return false;
//        }
        return true;
    }
}
