<?php

declare(strict_types=1);

namespace bizley\podium\tests\widgets;

use bizley\podium\client\base\Alert as AlertComponent;
use keystone\common\widgets\alert\Alert;
use tests\AppTestCase;
use yii\helpers\ArrayHelper;

/**
 * Class AlertWidgetTest
 * @package tests\components
 */
class AlertWidgetTest extends AppTestCase
{
    /**
     * @return array additional mocked app config
     */
    public static function config(): array
    {
        return ArrayHelper::merge(parent::config(), [
            'components' => [
                'i18n' => [
                    'translations' => [
                        'common.*' => [
                            'class' => \yii\i18n\PhpMessageSource::class,
                            'sourceLanguage' => 'en',
                            'forceTranslation' => true,
                            'basePath' => '@app/messages',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testOneAlert(): void
    {
        $alert = new AlertComponent();
        $alert->danger('test-one');

        $out = Alert::widget();

        $this->assertEquals(<<<HTML
<div class="alert alert-danger alert-dismissible fade show" role="alert">test-one<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
HTML
            , $out);
    }

    /**
     * @runInSeparateProcess
     */
    public function testMultipleAlerts(): void
    {
        $alert = new AlertComponent();
        $alert->danger('test-one');
        $alert->success('test-two');
        $alert->info('test-three');

        $out = Alert::widget();

        $this->assertEquals(<<<HTML
<div class="alert alert-danger alert-dismissible fade show" role="alert">test-one<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
<div class="alert alert-success alert-dismissible fade show" role="alert">test-two<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
<div class="alert alert-info alert-dismissible fade show" role="alert">test-three<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
HTML
            , $out);
    }
}
