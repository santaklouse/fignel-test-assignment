<?php

namespace FignelTestAssignment\Helper;

//use BlueM\Tree;
//use BlueM\Tree\Node;

use FignelTestAssignment\Tree;

class FigmaJSONHelper
{
    private array $json;
    private Tree $documentTree;

    public function getNodeById(string $id)
    {
        return $this->getDocumentTree()->getNodeById($id);
    }

    public function __construct(array $json)
    {
        $this->setJson($json);
        $this->createDocumentTree();
    }

    private function setJson(array $data): void
    {
        $this->json = $data;
    }

    /**
     * @return array
     */
    public function getJson($index = NULL): array
    {
        if (is_null($index)) {
            return $this->json;
        }
        return $this->json[$index];
    }

    public function getDocumentTree():Tree
    {
        return $this->documentTree;
    }

    private function setDocumentTree(array $document)
    {
//        var_dump(new Tree($document['children']));exit;
//        var_export($document['children']);exit;
        $this->documentTree = new Tree($document);
    }

    private function createDocumentTree()
    {

        $this->setDocumentTree(
            $this->getJson('document')
        );
    }

}
