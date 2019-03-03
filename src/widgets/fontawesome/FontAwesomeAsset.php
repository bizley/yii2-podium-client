<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\fontawesome\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @package bizley\podium\client\widgets\fontawesome\assets
 *
 * FontAwesome 5 Free CSS
 * https://fontawesome.com/
 */
class FontAwesomeAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@npm/fortawesome--fontawesome-free/';

    /**
     * {@inheritdoc}
     */
    public $css = ['css/all.css'];

    /**
     * {@inheritdoc}
     */
    public $publishOptions = [
        'only' => [
            'css/all.css',
            'webfonts/*'
        ],
    ];
}
