<?php

namespace FignelTestAssignment\Helper;

use Exception;

class JSONMockLoader
{
    /**
     * @throws Exception
     */
    public static function load(string $name):array
    {
        $path = getenv('BASE_PATH') . "/mock/$name.json";

        if (!file_exists($path)) {
            throw new Exception("File '$path' not exists");
        }

        $fileContent = file_get_contents($path);

        $data = [];
        try {
            $data = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo "JSON decode error: $exception->getMessage()";
        }
        return $data;
    }
}


