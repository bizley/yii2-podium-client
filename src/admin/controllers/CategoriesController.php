<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\controllers;

use bizley\podium\client\admin\forms\CategoryForm;
use bizley\podium\client\admin\PodiumAdmin;
use bizley\podium\client\enums\Role;
use bizley\podium\client\filters\PodiumAccessControl;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class CategoriesController
 * @package bizley\podium\client\admin\controllers
 *
 * @property PodiumAdmin $module
 */
class CategoriesController extends \yii\web\Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => PodiumAccessControl::class,
                'podium' => $this->module->module,
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
     * @param array $links
     * @param bool $parent
     */
    public function setBreadcrumbs(array $links = [], bool $parent = true): void
    {
        $this->view->params['breadcrumbs'] = array_merge(
            $parent ? [
                [
                    'label' => Yii::t('podium.admin.link', 'dashboard'),
                    'url' => ['main/index'],
                ]
            ] : [],
            $links
        );
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->setBreadcrumbs([Yii::t('podium.admin.header', 'categories')]);

        return $this->render('index.twig', [
            'categories' => $this->module->api->category->getCategories(
                null,
                [
                    'defaultOrder' => [
                        'sort' => SORT_ASC
                    ],
                ],
                false
            ),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $categories = ArrayHelper::map(
            $this
                ->module
                ->api
                ->category
                ->getCategories(
                    null,
                    [
                        'defaultOrder' => [
                            'sort' => SORT_ASC
                        ],
                    ],
                    false
                )
                ->getModels(),
            'id',
            'name'
        );

        $model = new CategoryForm(
            $this->module->api,
            $this->module->notify,
            $categories
        );

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->module->notify->success(Yii::t('podium.admin.success', 'category.added'));

            return $this->redirect(['index']);
        }

        $this->setBreadcrumbs([
            [
                'label' => Yii::t('podium.admin.link', 'categories'),
                'url' => ['categories/index'],
            ],
            Yii::t('podium.admin.header', 'category.add')
        ]);

        return $this->render('create.twig', [
            'model' => $model,
            'categories' => [-1 => '-- ' . Yii::t('podium.admin.label', 'after.beginning')] + $categories,
        ]);
    }
}
