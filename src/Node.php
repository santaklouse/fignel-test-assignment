<?php

namespace FignelTestAssignment;

use ArrayAccess;
use FignelTestAssignment\Figma\Element;

class Node implements ArrayAccess
{
    private $id;

    private array $children = [];

    private array $data = [];

    private $parent = NULL;

    private Element $element;

    public function __construct($array, $parent)
    {
        $this->setId($array['id']);

        $data = $array;
        unset($data['children']);
        $this->setData($data);

        $this->setParent($parent);
        $this->setElement(Element::factory($array['type'], $this));
        $this->setChildren($array['children'] ?? [], $this);
    }

    /**
     * @return null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param null $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function getById($id): ?Node
    {
        if ($this->getId() === $id) {
            return $this;
        }

        if (!$this->hasChildren()) {
            return NULL;
        }

        foreach ($this->getChildren() as $child) {
            $node = $child->getById($id);
            if ($node) {
                return $node;
            }
        }

        return NULL;
    }

    /**
     * @return mixed
     */
    private function isChildExists($id):bool
    {
        return !!$this->getById($id);
    }

    public function hasChildren() : bool
    {
        return !empty($this->getChildren());
    }

    /**
     * @param Node $node
     */
    public function setChild(Node $node): void
    {
        $this->children[] = $node;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children, $parent): void
    {
        foreach ($children as $child)
        {
            $this->setChild(new Node($child, $parent));
        }
    }

    /**
     * @return Element
     */
    public function getElement(): Element
    {
        return $this->element;
    }

    /**
     * @param Element $element
     */
    public function setElement(Element $element): void
    {
        $this->element = $element;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getData($key = NULL)
    {
        if (is_null($key)) {
            return $this->data;
        }
        return $this->data[$key] ?? NULL;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    public function offsetSet($offset, $value):void {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset):bool {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset):void {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset] ?? NULL;
    }

}
