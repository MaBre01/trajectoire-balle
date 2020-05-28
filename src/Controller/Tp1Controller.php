<?php

namespace App\Controller;

use App\Chart\Tp1;

class Tp1Controller extends AbstractController
{
    public function get()
    {
        $this->render('tp1/get.html.php');
    }

    public function post()
    {
        $distance = $_POST["distance"];
        
        $chart = new Tp1($distance);
        $chart->render();
    }
}