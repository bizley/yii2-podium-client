<?php

declare(strict_types=1);

/* @var $this \yii\web\View */

$currentYear = (int) date('Y');
$startYear = 2018;

$period = $startYear;
if ($currentYear > $startYear) {
    $period .= '-' . $currentYear;
}

?>
<hr>
<footer>
    <p>&copy; <?= $period ?> Podium v<?= $this->context->module->getVersion() ?></p>
</footer>