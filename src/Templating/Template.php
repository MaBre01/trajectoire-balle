<?php

namespace App\Templating;

class Template
{
    public const TEMPLATE_FOLDER = __DIR__ . '/../../template/';
    public const DEFAULT_LAYOUT = 'layout.html.php';

    private ?string $layoutTemplate;

    public function __construct(?string $layoutTemplate = null)
    {
        $this->layoutTemplate = $layoutTemplate;
    }

    public function create(string $fileTemplate, array $variables = [])
    {   
        $file = self::TEMPLATE_FOLDER . $fileTemplate;
        if (!file_exists($file)) {
            throw new TemplateException("Template $file does not exist");
        }

        extract($variables);

        ob_start();
        require($file);
        $pageContent = ob_get_clean();

        if (null !== $this->layoutTemplate && file_exists(self::TEMPLATE_FOLDER . $this->layoutTemplate)) {
            require(self::TEMPLATE_FOLDER . $this->layoutTemplate);
        } else {
            if (!file_exists(self::TEMPLATE_FOLDER . self::DEFAULT_LAYOUT)) {
                throw new TemplateException("Default layout file does not exist");
            }
            require(self::TEMPLATE_FOLDER . self::DEFAULT_LAYOUT);
        }
    }
}