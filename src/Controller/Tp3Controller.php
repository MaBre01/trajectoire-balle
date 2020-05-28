<?php

namespace App\Controller;

use App\Chart\Tp3;

class Tp3Controller extends AbstractController
{
    public function get()
    {
        $this->render('tp3/get.html.php');
    }

    public function post()
    {
        $initialSpeed = $_POST['initialSpeed'];
        $angle = $_POST['angle'];

        $chart = new Tp3($angle, $initialSpeed);
        $chart->render();
    }
}