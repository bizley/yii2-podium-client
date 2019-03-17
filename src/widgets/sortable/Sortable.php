<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\sortable;

use bizley\podium\client\widgets\sortable\assets\SortableAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\Widget;
use yii\helpers\Url;

/**
 * Class Sortable
 * @package bizley\podium\client\widgets\sortable
 */
class Sortable extends Widget
{
    /**
     * @var array
     */
    public $action;

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

        $url = Url::to($this->action);

        $this->view->registerJs(<<<JS
$("#{$this->id}").sortable({
    handle: ".handle",
    ghostClass: "sortable-ghost",
    onEnd: function(e) {
        let handle = $(e.item).find(".handle");
        handle.removeClass("btn-outline-secondary").addClass("text-muted").find(".fa-arrows-alt-v").removeClass("fa-arrows-alt-v").addClass("fa-circle-notch fa-spin");
        $.get("{$url}", {newOrder: e.newIndex, oldOrder: e.oldIndex})
            .always(function(data) {
                handle.removeClass("text-muted").find(".fa-circle-notch").removeClass("fa-circle-notch fa-spin").addClass("fa-arrows-alt-v");
            })
            .done(function(data) {
                handle.addClass("btn-outline-success");
            })
            .fail(function() {
                handle.addClass("btn-outline-danger");
            });        
    }
});
JS
        );

        return Html::endTag('div');
    }
}
