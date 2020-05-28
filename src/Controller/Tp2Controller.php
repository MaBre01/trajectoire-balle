<?php

namespace App\Controller;

use App\Chart\Tp2;

class Tp2Controller extends AbstractController
{
    public function get()
    {
        $this->render('tp2/get.html.php');
    }

    public function post()
    {
        $angle = $_POST['angle'];
        $initialSpeed = $_POST['initialSpeed'];
        $fluidCoefficient = $_POST['fluidCoefficient'];
        $bulletWeight = $_POST['bulletWeight'];
        $distance = $_POST['distance'];

        $chart = new Tp2($angle, $initialSpeed, $fluidCoefficient, $bulletWeight, $distance);
        $chart->render();
    }
}