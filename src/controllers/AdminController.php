<?php

declare(strict_types=1);

namespace bizley\podium\client\controllers;

use bizley\podium\client\enums\Role;
use bizley\podium\client\filters\PodiumAccessControl;
use bizley\podium\client\forms\SettingsForm;
use bizley\podium\client\PodiumClient;
use Yii;
use yii\web\Response;

/**
 * Class AdminController
 * @package bizley\podium\client\controllers
 *
 * @property PodiumClient $module
 */
class AdminController extends \yii\web\Controller
{
    public $layout = 'admin.twig';

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => PodiumAccessControl::class,
                'podium' => $this->module,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Role::ADMIN],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index.twig');
    }

    /**
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSettings()
    {
        $model = new SettingsForm($this->module->getConfig());

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->module->notify->success(Yii::t('podium.client.alert', 'setting.save.success'));
            } else {
                $this->module->notify->danger(Yii::t('podium.client.alert', 'setting.save.error'));
            }

            return $this->refresh();
        }

        return $this->render('settings.twig', [
            'model' => $model,
        ]);
    }
}
