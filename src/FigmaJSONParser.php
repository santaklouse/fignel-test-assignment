<?php

namespace FignelTestAssignment;

use FignelTestAssignment\Helper\JSONMockLoader;
use FignelTestAssignment\Helper\FigmaJSONHelper;

class FigmaJSONParser
{
    private FigmaJSONHelper $figmaJsonHelper;

    public function __construct()
    {
        $this->figmaJsonHelper = new FigmaJSONHelper(
            JSONMockLoader::load('figma')
        );
    }

    /**
     */
    public function getRoot()
    {
        return $this->figmaJsonHelper->getDocumentTree();
    }

}
