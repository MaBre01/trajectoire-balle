<?php

namespace App\Chart;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class Tp1
{
    public const CHART_WIDTH = 1600;
    public const CHART_HEIGHT = 1200;

    private int $width;
    private int $height;
    private float $distance;
    private array $datas;

    public function __construct(float $distance, ?int $width = null, ?int $height = null)
    {
        $this->distance = $distance;
        $this->width = ($width === null) ? self::CHART_WIDTH : $width;
        $this->height = ($height === null) ? self::CHART_HEIGHT : $height;

        $this->datas = $this->createPlots();
    }

    private function createPlots(): array
    {
        $datas = [];

        $coefficient = ($this->distance / pow($this->distance, 2));

        for ($i = 0; $i <= $this->distance; $i++) {
            $datas[] = ((- $coefficient * pow($i, 2)) + $i) / 100;
        }

        return $datas;
    }

    public function render(): void
    {
        $graph = new Graph\Graph($this->width, $this->height);
        $graph->SetScale('linlin');
        $graph->SetMargin(50, 15, 40, 30);
        $graph->SetMarginColor('white');
        $graph->SetFrame(true, 'blue', 3);

        $graph->title->Set('Label background');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);

        $graph->subtitle->SetFont(FF_ARIAL, FS_NORMAL, 10);
        $graph->subtitle->SetColor('darkred');
        $graph->subtitle->Set('"LABELBKG_NONE"');
        $graph->SetAxisLabelBackground(LABELBKG_NONE, 'orange', 'red', 'lightblue', 'red');
        
        $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 9);
        $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 9);
        $graph->xgrid->Show();

        $p1 = new Plot\LinePlot($this->datas);
        $graph->Add($p1);
        
        $graph->Stroke();
    }
}