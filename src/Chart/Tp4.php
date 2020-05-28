<?php

namespace App\Chart;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class Tp4
{
    public const CHART_WIDTH = 1600;
    public const CHART_HEIGHT = 1200;
    public const G = 9.81;
    public const AIR_DENSITY = 1.225;

    private int $width;
    private int $height;

    private float $angle;
    private float $initialSpeed;
    private float $bulletWeight;
    private float $bulletDiameter;

    private array $datas;

    public function __construct(float $angle, float $initialSpeed, float $bulletWeight, float $bulletDiameter, ?int $width = null, ?int $height = null)
    {
        $this->angle = deg2rad($angle);
        $this->initialSpeed = $initialSpeed;
        $this->bulletWeight = $bulletWeight;
        $this->bulletDiameter = $bulletDiameter;

        $this->width = ($width === null) ? self::CHART_WIDTH : $width;
        $this->height = ($height === null) ? self::CHART_HEIGHT : $height;

        $this->datas = $this->createPlots();
    }

    private function createPlots(): array
    {
        $datas = [];
    $speed = $this->initialSpeed;
    // calcul des données d'ordonnées
    for ($i = 0; $i <= 10000; $i++) {
        $datas[$i] = ($i * tan($this->angle)) - ((self::G * pow($i, 2)) / (2 * pow($speed, 2) * pow(cos($this->angle), 2)));
        $fd = $this->calculatePowerResistance($speed);
      // echo("speedBefore: ". $speed. "<br>");
        $speed = ($fd * $speed)  + $speed;
     //   echo("cx: ". $fd). "<br>";
     //   echo("speedAfter: ". $speed. "<br>");
     //   echo("------------------------END--------------------------------------- ". "<br>". "<br>");
        if ($speed <= pow(9.9881371448434, -30)) {
            break;
        }
    }
    return $datas;
    }

    public function render()
    {
        $graph = new Graph\Graph($this->width, $this->height);
        $graph->SetScale('linlin', 0, 100, 0, 500);
        $graph->SetMargin(150, 15, 40, 30);
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
        $p1 = new Plot\LinePlot($this->datas);
        $graph->Add($p1);
        
        // Output graph
        $graph->Stroke();
    }

    private function calculateBalisticCoefficient(float $speed): float
    {
        // Cx = m*(v2² - v1²) /  (d * (S*v²*ρ ))
        $deltaSquareSpeed = (pow($speed * 0.99, 2) - pow($speed, 2));
       // $powerResistance = $this->calculatePowerResistance($speed);
        $surface = (pi() * pow($this->bulletDiameter / 2, 2));
        $squareSpeed = pow(0.99* $speed, 2);
        return ($this->bulletWeight * $deltaSquareSpeed) / ($this->bulletDiameter * $surface * $squareSpeed * self::AIR_DENSITY);
       // return (2 * $powerResistance) / ($surface * $squareSpeed * self::AIR_DENSITY);
    }

    private function calculatePowerResistance(float $speed): float
    {
        $deltaSquareSpeed = (pow($speed * 0.99, 2) - pow($speed, 2));
      ///  $Fd = (0.5 * $this->bulletWeight * $squareSpeed) / $this->bulletDiameter;
        $surface = (pi() * pow($this->bulletDiameter / 2, 2));
       //  return $Fd;
        return $fd = 0.3 * self::AIR_DENSITY * $surface * 0.3 * (pow($speed * 0.99, 2) - pow($speed, 2));
    }
}
