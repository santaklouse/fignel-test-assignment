<?php

namespace FignelTestAssignment\Figma\Elements;

use FignelTestAssignment\Figma\Element;

class Document extends Element
{
    protected string $tagName = 'div';

    protected array $defaultStyles = [
        'position: relative',
        'overflow: hidden',
    ];

}
