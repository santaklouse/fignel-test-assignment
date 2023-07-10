<?php

namespace FignelTestAssignment\Figma\Elements;

use FignelTestAssignment\Figma\Element;

class RawText extends Element
{
    private $text;
    protected string $tagName = '';

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function html():string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function css(): string
    {
        return '';
    }
}
