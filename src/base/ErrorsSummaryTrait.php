<?php

declare(strict_types=1);

namespace bizley\podium\client\base;

use yii\bootstrap4\Html;

/**
 * Trait ErrorsSummaryTrait
 * @package bizley\podium\client\base
 */
trait ErrorsSummaryTrait
{
    /**
     * Returns formatted summary of errors wrapped as list.
     * @param string $header text to be printed before the list
     * @param array $errors
     * @return string
     */
    public function getErrorsSummary(string $header, array $errors): string
    {
        $summary = $header;
        $summary .= Html::beginTag('ul');
        foreach ($errors as $error) {
            $summary .= Html::tag('li', $error);
        }
        $summary .= Html::endTag('ul');

        return $summary;
    }
}
