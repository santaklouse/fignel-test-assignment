<?php

namespace FignelTestAssignment;

use Exception;

class Tree
{
    private Node $tree;

    public function __construct($array)
    {
        $this->build($array);
    }

    public function build($document)
    {
        $this->tree = new Node($document, NULL);
    }

    /**
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this->tree, $name)) {
            throw new Exception('Method "' . $name . '" not exists.');
        }

        return $this->tree->$name($arguments);
    }

    public function getNodeById($id, $node = NULL)
    {
        if (!$node) {
            $node = $this->tree;
        }

        if ($node->getId() === $id) {
            return $node;
        }

        if (!$node->hasChildren()) {
            return NULL;
        }

        foreach ($node->getChildren() as $child) {
            $node = $this->getNodeById($id, $child);
            if ($node) {
                return $node;
            }
        }

        return NULL;
    }

}
