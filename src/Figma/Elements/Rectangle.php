<?php

namespace FignelTestAssignment\Figma\Elements;

use FignelTestAssignment\Figma\Element;
use FignelTestAssignment\Node;

class Rectangle extends Element
{
    protected string $tagName = 'div';

    public function __construct(Node $node)
    {
        parent::__construct($node);

        $this->data['style'][] = 'position: absolute';
    }
}
