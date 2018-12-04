<?php

declare(strict_types=1);

namespace bizley\podium\client\widgets\chart;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Chart
 * @package bizley\podium\client\widgets\chart
 */
class Chart extends Widget
{
    /**
     * @var string
     */
    public $color = '#078cf9';

    /**
     * @var array
     */
    public $data = [];

    /**
     * @return string
     */
    public function run(): string
    {
        $this->prepareLabels();
        $this->prepareJs();

        return Html::tag('canvas', '', ['id' => $this->id, 'style' => 'width:100%; height:100px']);
    }

    /**
     * @return array
     */
    public function prepareLabels(): array
    {
        $labels = [
            1 => Yii::t('podium.client.label', 'month.january'),
            2 => Yii::t('podium.client.label', 'month.february'),
            3 => Yii::t('podium.client.label', 'month.march'),
            4 => Yii::t('podium.client.label', 'month.april'),
            5 => Yii::t('podium.client.label', 'month.may'),
            6 => Yii::t('podium.client.label', 'month.june'),
            7 => Yii::t('podium.client.label', 'month.july'),
            8 => Yii::t('podium.client.label', 'month.august'),
            9 => Yii::t('podium.client.label', 'month.september'),
            10 => Yii::t('podium.client.label', 'month.october'),
            11 => Yii::t('podium.client.label', 'month.november'),
            12 => Yii::t('podium.client.label', 'month.december'),
        ];

        $currentMonth = (int) date('n');

        if ($currentMonth === 12) {
            return array_values($labels);
        }

        return array_merge(
            array_slice($labels, $currentMonth + 1, 12 - $currentMonth),
            array_slice($labels, 1, $currentMonth)
        );
    }

    public function prepareJs(): void
    {
        $view = $this->getView();

        ChartJsAsset::register($view);

        $monthsArray = Json::encode($this->prepareLabels());
        $dataArray = Json::encode($this->data);

        $max = 0;
        foreach ($this->data as $data) {
            if ($data > $max) {
                $max = $data;
            }
        }
        $max += (int) round($max / 2);

        $view->registerJs(<<<JS
var ctx = document.getElementById("{$this->id}").getContext("2d");
var gradient = ctx.createLinearGradient(0, 0, 0, 120);
gradient.addColorStop(0, Chart.helpers.color("{$this->color}").alpha(0.3).rgbString());
gradient.addColorStop(1, Chart.helpers.color("{$this->color}").alpha(0).rgbString());
new Chart(ctx, {
    type: "line",
    data: {
        labels: {$monthsArray},
        datasets: [{
            borderColor: "{$this->color}",
            borderWidth: 2,
            backgroundColor: gradient,
            pointBackgroundColor: "{$this->color}",
            data: {$dataArray},
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        title: {display: false},
        tooltips: {
            enabled: true,
            intersect: false,
            mode: "nearest",
            yPadding: 10,
            xPadding: 10,
            caretPadding: 0,
            displayColors: false,
            backgroundColor: "{$this->color}",
            titleFontColor: "#ffffff",
            cornerRadius: 4,
            footerSpacing: 0,
            titleSpacing: 0
        },
        legend: {display: false},
        hover: {mode: "index"},
        scales: {
            xAxes: [{display: false}],
            yAxes: [{
                display: false,
                ticks: {
                    max: {$max},
                    display: false,
                    beginAtZero: true
                }
            }]
        },
        elements: {
            point: {
                radius: 0,
                borderWidth: 0,
                hoverRadius: 0,
                hoverBorderWidth: 0
            }
        },
        layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        }
    }
});
JS
);
    }
}
