<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
    public function __construct(private string $basePath) {}

    public function render(string $template, array $data = [])
    {
        ob_start(); //output buffering
        extract($data, EXTR_OVERWRITE); //extacting data to be used in the template
        include $this->resolve($template); //adding path to template

        $output = ob_get_contents(); //creating OB content
        ob_end_clean();
        return $output; //return buffered output
    }

    public function resolve(string $path)
    {
        return "{$this->basePath}/{$path}";
    }
}
