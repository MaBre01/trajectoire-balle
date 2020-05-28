<?php

namespace App\Chart;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class Tp2
{
    public const CHART_WIDTH = 1600;
    public const CHART_HEIGHT = 1200;

    public const G = 9.81;

    private int $width;
    private int $height;
    private float $distance;
    private array $yDatas;
    private array $xDatas;

    private float $angle;
    private float $initialSpeed;
    private float $fluidCoefficient;
    private float $bulletWeight;

    private float $xMax;
    private float $yMax;

    public function __construct(
        float $angle,
        float $initialSpeed,
        float $fluidCoefficient,
        float $bulletWeight,
        float $distance,
        ?int $width = null,
        ?int $height = null
    )
    {
        $this->angle = $this->convertAngle($angle);
        $this->initialSpeed = $initialSpeed;
        $this->fluidCoefficient = $fluidCoefficient;
        $this->bulletWeight = $bulletWeight;
        $this->distance = $distance;

        $this->width = ($width === null) ? self::CHART_WIDTH : $width;
        $this->height = ($height === null) ? self::CHART_HEIGHT : $height;

        $datas = $this->createPlots();
        $this->yDatas = $datas['yDatas'];
        $this->xDatas = $datas['xDatas'];
    }

    private function createPlots(): array
    {
        $yDatas = [];
        $xDatas = [];

        $k =  $this->fluidCoefficient / $this->bulletWeight;

        // calcul des différents coeff
        $a = $this->initialSpeed / $k * cos($this->angle);
        $c = self::G / pow($k, 2);
        $b = $this->initialSpeed / $k * sin($this->angle) + $c;

        // calcul des données d'ordonnées
        for ($i = 0; $i <= $this->distance; $i++){
            $xDatas[$i] = $i;
            $yDatas[$i] = ( ($b/$a) * $i) + ($c * log( 1 - ($i / $a)) ); 
        }

        $this->xMax = ((pow($this->initialSpeed, 2)) * cos($this->angle) * sin($this->angle)) / ($k*$this->initialSpeed*sin($this->angle) + self::G);
        $this->yMax =  (($b/$a) * $this->xMax) + ($c*log( 1 - ($this->xMax / $a)));

        return [
            'xDatas' => $xDatas,
            'yDatas' => $yDatas
        ];
    }

    public function render(): void
    {
        $graph = new Graph\Graph($this->width, $this->height);
        $graph->SetScale('linlin', 0, $this->yMax + 10, 0, count($this->yDatas));
        $graph->SetMargin(30, 15, 40, 30);
        $graph->SetMarginColor('white');
        $graph->SetFrame(false);

        $graph->title->Set('Label background');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 12);

        $graph->subtitle->SetFont(FF_ARIAL, FS_NORMAL, 10);
        $graph->subtitle->SetColor('darkred');
        $graph->subtitle->Set('"LABELBKG_NONE"');

        $graph->SetAxisLabelBackground(LABELBKG_NONE, 'orange', 'red', 'lightblue', 'red');
        // Use Ariel font
        $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 9);
        $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 9);
        $graph->xgrid->Show();

        // Create the plot line
        $p1 = new Plot\LinePlot($this->yDatas, $this->xDatas);
        $graph->Add($p1);

        // Output graph
        $graph->Stroke();
    }

    private function convertAngle(float $angle): float
    {
        return $angle * pi() / 180;
    }
}