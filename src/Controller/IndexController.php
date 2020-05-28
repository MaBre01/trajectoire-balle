<?php

namespace App\Controller;

class IndexController extends AbstractController
{
    public function index()
    {
        $this->render('index/index.php');
    }
}