<?php

namespace App\Controller;

use App\Chart\Tp4;

class Tp4Controller extends AbstractController
{
    public function get()
    {
        $this->render('tp4/get.html.php');
    }

    public function post()
    {
        $angle = $_POST['angle'];
        $initialSpeed = $_POST['initialSpeed'];
        $bulletWeight = $_POST['bulletWeight'];
        $bulletDiameter = $_POST['bulletDiameter'];

        $chart = new Tp4($angle, $initialSpeed, $bulletWeight, $bulletDiameter);
        $chart->render();
    }
}