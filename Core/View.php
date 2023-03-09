<?php

namespace Core;

class View
{
    public function render($template, $vars =[])
    {
        $templatePath = $this->getTemplatePath($template);

        if(!is_file($templatePath))
        {
            throw new \InvalidArgumentException(
                sprintf('Шаблон "%s" не найден в директории "%s"',$template,$templatePath)
            );
        }

        extract($vars);
        ob_start();
        ob_implicit_flush(0);

        try{
            require $templatePath;
        }catch(\Exception $e){
            ob_end_clean();
            throw $e;
        }

        echo ob_get_clean();
    }

    private function getTemplatePath($template){
        return 'Page/'.$template.'.php';
    }
}