<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\sortable;

use bizley\podium\client\widgets\sortable\assets\SortableAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\Widget;

/**
 * Class Sortable
 * @package bizley\podium\client\widgets\sortable
 */
class Sortable extends Widget
{
    public function init(): void
    {
        parent::init();
        echo Html::beginTag('div', ['id' => $this->id]);
    }

    /**
     * @return string
     */
    public function run(): string
    {
        SortableAsset::register($this->view);
        $this->view->registerJs("\$(\"#{$this->id}\").sortable({
    handle: \".handle\",
    ghostClass: \"sortable-ghost\",
    onEnd: function(e) {
        console.log(e.newIndex, e.oldIndex);
    }
});");

        return Html::endTag('div');
    }
}
