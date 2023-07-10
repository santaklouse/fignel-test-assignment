<?php

namespace FignelTestAssignment\Figma\Elements;

use FignelTestAssignment\Figma\Element;
use FignelTestAssignment\Helper\Utils;

class Canvas extends Element
{
    protected string $tagName = 'div';

    public function css():string
    {
        $styles = parent::css();
        $backgroundColor = $this->node->getData('backgroundColor');

        if (!$backgroundColor)
            return $styles;

        $color = Utils::color2Rgba($backgroundColor);
        if (empty($color))
            $styles;

        $cssString = "body {\nbackground-color: {$color}\n}\n";

        return $cssString . $styles;
    }
}
