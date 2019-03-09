<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\sortable\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class SortableAsset
 * @package bizley\podium\client\widgets\sortable\assets
 *
 * https://sortablejs.github.io/Sortable/
 * https://github.com/SortableJS/jquery-sortablejs
 */
class SortableAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@npm/jquery-sortablejs/';

    /**
     * {@inheritdoc}
     */
    public $js = ['jquery-sortable.min.js'];

    /**
     * {@inheritdoc}
     */
    public $publishOptions = [
        'only' => [
            'jquery-sortable.min.js'
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public $depends = [JqueryAsset::class];
}
