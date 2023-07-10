<?php

namespace FignelTestAssignment\Figma\Elements;

use FignelTestAssignment\Figma\Element;

class TextSpan extends Element
{
    protected string $tagName = 'span';

    private $text;

    public function __construct($className, $text)
    {
        $this->text = $text;
        $this->className = $className;
    }

    public function html()
    {
        return "<{$this->tagName} class=\"{$this->className}\">{$this->text}</{$this->tagName}>";
    }

}
