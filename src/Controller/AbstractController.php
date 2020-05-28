<?php

namespace App\Controller;

use App\Templating\Template;

abstract class AbstractController
{   
    public function render(string $pathFile, array $variables = [], ?string $layoutTemplate = null)
    {
        $template = new Template($layoutTemplate);
        $template->create($pathFile, $variables);
    }
}