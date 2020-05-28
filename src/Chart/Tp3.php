<?php

namespace App\Chart;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class Tp3
{
    public const CHART_WIDTH = 1600;
    public const CHART_HEIGHT = 1200;

    public const G = 9.81;

    private int $width;
    private int $height;
    private float $distance;
    private array $yDatas;

    private float $angle;
    private float $initialSpeed;
    private float $fluidCoefficient;
    private float $bulletWeight;

    private float $xMax;
    private float $yMax;

    public function __construct(float $initialSpeed, float $angle, ?int $width = null, ?int $height = null)
    {
        $this->initialSpeed = $initialSpeed;
        $this->angle = $this->convertAngle($angle);

        $this->width = ($width === null) ? self::CHART_WIDTH : $width;
        $this->height = ($height === null) ? self::CHART_HEIGHT : $height;

        $this->yDatas = $this->createPlots();
    }

    private function createPlots(): array
    {
        $ydata = [];
        $delta = pow(tan($this->angle),2);
        $s1 = (- tan($this->angle)) + (sqrt($delta) /  ( - self::G  / ( 2* pow($this->initialSpeed, 2) * pow(cos($this->angle), 2))));
        $s2 =   (- tan($this->angle)) - (sqrt($delta) /   ( - self::G  / (2 * pow($this->initialSpeed, 2) * pow(cos($this->angle), 2))));
        $this->xMax = max($s1, $s2);

        // calcul des données d'ordonnées
        for ($i = 0; $i <= $this->xMax; $i++){
            $pointDeChute = 0;
            $tmp = ($i * tan($this->angle)) - ( (self::G * pow($i, 2)) / (2 * pow($this->initialSpeed, 2) * pow(cos($this->angle), 2)));
            if ($i > 0 && $tmp == 0 && $pointDeChute == 0) {
                $pointDeChute = $i;
            }
            $ydata[$i] =  ($i * tan($this->angle)) - ( (self::G * pow($i, 2)) / (2 * pow($this->initialSpeed, 2) * pow(cos($this->angle), 2)));
        }
        $this->yMax = max($ydata);

        return $ydata;
    }

    public function render()
    {
        $graph = new Graph\Graph($this->width, $this->height);
        $graph->SetScale('linlin', 0, $this->yMax + (0.1 * $this->yMax), 0,  $this->xMax + (0.1 * $this->xMax));
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
        $p1 = new Plot\LinePlot($this->yDatas);
        $graph->Add($p1);
        
        // Output graph
        $graph->Stroke();
    }

    private function convertAngle(float $angle): float
    {
        return $angle * pi() / 180;
    }
}