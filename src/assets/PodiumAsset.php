<?php

namespace bizley\podium\client\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

/**
 * Class PodiumAsset
 * @package bizley\podium\client\assets
 */
class PodiumAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bizley/podium/client/assets/src';

    /**
     * @var array
     */
    public $css = ['podium.css'];

    /**
     * @var array
     */
    public $js = [];

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
