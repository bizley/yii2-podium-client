<?php

namespace bizley\podium\client\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class ChartJsAsset
 * @package bizley\podium\client\assets
 */
class ChartJsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@npm/chart.js/dist';

    /**
     * @var array
     */
    public $js = ['Chart.bundle.min.js'];

    /**
     * @var array
     */
    public $publishOptions = [
        'only' => ['Chart.bundle.min.js'],
    ];

    /**
     * @var array
     */
    public $depends = [JqueryAsset::class];
}
