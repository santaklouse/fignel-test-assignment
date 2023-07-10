<?php

namespace FignelTestAssignment\Helper;

class View
{
    const TEMPLATES_PATH = '/view/';

    public static function render($name, $variables = [], $returnOnly = FALSE)
    {
        $path = getenv('BASE_PATH') . self::TEMPLATES_PATH . $name . '.html';

        if (!file_exists($path))
            throw new \Exception('Template "' . $path . '" not exists.');

        $contents = file_get_contents($path);

        $result = str_replace(
            array_map(fn($n) => '{'.$n.'}', array_keys($variables)),
            array_values($variables),
            $contents
        );

        if ($returnOnly)
            return $result;

        echo $result;
    }
}
