<?php

namespace FignelTestAssignment\Figma;

use FignelTestAssignment\Helper\Utils;
use FignelTestAssignment\Node;

abstract class Element
{
    protected array $defaultStyles = [];

    protected string $className;

    protected string $tagName;

    protected array $data;
    protected array $json;

    protected Node $node;

    public function __construct(Node $node)
    {
        $this->setNode($node);

        $data = $node->getData();

        if (array_key_exists('children', $data)) {
            unset($data['children']);
        }

        $this->json = $data;

        $this->className = $this->generateClassName();

        $this->data = [
            "className" => $this->className,
            "tagName" => $this->tagName,
            "key" => $data['id'],
            "style" => array_merge($this->defaultStyles, Styles::build($node)),
            "html" => [],
            "stylesTable" => [],
            'json' => $data
        ];

    }

    private function generateClassName():string
    {
        $safeNodeId = Utils::escapeSpecialChars($this->getNode()->getId());
        return "item-{$safeNodeId}";
    }

    public static function factory($name, $params = [])
    {
        $name = ucfirst(strtolower($name));
        $className = '\FignelTestAssignment\Figma\Elements\\' . $name;
        if (!class_exists($className)) {
            throw new \Exception('Class ' . $className . ' not exists');
        }
        return new $className($params);
    }

    /**
     * @return string
     */
    public function html()
    {
        if (!$this->getNode()->hasChildren()) {
            return "<{$this->tagName} class=\"{$this->className}\"></{$this->tagName}>";
        }

        $result = [];

        foreach ($this->getNode()->getChildren() as $child)
        {
            $result[] = $child->getElement()->html();
        }
        $result = join('', $result);
        return "<{$this->tagName} class=\"{$this->className}\">{$result}</{$this->tagName}>";
    }

    public function css():string
    {
        $styles = join(";\n", $this->data['style']);

        $result = '';
        if (!empty($styles)) {
            $result .= ".{$this->className} {\n{$styles};\n}\n";
        }

        if ($this->getNode()->hasChildren()) {
            $result .= join("\n\n", array_map(
                fn($child) => $child->getElement()->css(),
                $this->getNode()->getChildren()
            ));
        }
        return $result;
    }

    public function __toString():string
    {
        return $this->html();
    }

    /**
     * @return Node
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    /**
     * @param Node $node
     */
    public function setNode(Node $node): void
    {
        $this->node = $node;
    }

}
