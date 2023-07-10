<?php

namespace FignelTestAssignment\Figma\Elements;

use FignelTestAssignment\Figma\Element;
use FignelTestAssignment\Figma\Styles;
use FignelTestAssignment\Helper\Arr;
use FignelTestAssignment\Node;

class Text extends Element
{
    protected string $tagName = 'div';

    private $text;

    public function __construct(Node $node)
    {
        parent::__construct($node);

        $this->text = $node->getData('characters');

        $this->data = array_merge($this->data, [
            "textContent" => $this->text,
            "stylesTable" => []
        ]);

        $this->data['stylesTable'] = $this->buildStylesTable($this->json['styleOverrideTable'] ?? []);
    }

    private static function overrideIndex2ClassName($index)
    {
        return "ovr-item-{$index}";
    }

    private function buildStylesTable(array $styleOverrideTable): array
    {
        $stylesTable = [];
        foreach($styleOverrideTable as $index => $table)
        {
            $className = self::overrideIndex2ClassName($index);
            $stylesTable[$className] = Styles::build($table);
        }
        return $stylesTable;
    }

    public function html():string
    {
        $characterStyleOverrides = $this->json['characterStyleOverrides'] ?? [];

        if (empty($characterStyleOverrides))
        {
            $textSpan = new TextSpan($this->className, $this->text);
            return $textSpan->html();
        }

        $elementsHtml = array_map(
            fn(Element $span) => $span->html(),
            $this->_buildSpans($characterStyleOverrides)
        );

        $html = join('', $elementsHtml);

        return  implode("<br class=\"ps\"/>", explode("\n", self::buildHtml($html)));
    }

    private function buildHtml(string $html):string
    {
        return "<{$this->tagName} class=\"{$this->className}\">{$html}</{$this->tagName}>";
    }

    public function css():string
    {
        $styles = join(";\n", $this->data['style']);

        $result = '';
        if (!empty($styles)) {
            $result .= ".{$this->className} {\n{$styles};\n}\n";
        }

        $stylesTable = $this->data['stylesTable'] ?? NULL;
        if (empty($stylesTable)) {
            return $result;
        }

        $data = $this->getNode()->getData();
        if (Arr::has($data, 'style.paragraphSpacing')) {
            $stylesTable["ps"] = Styles::paragraphSpacingStyles(
                Arr::get($data, 'style.paragraphSpacing')
            );
        }

        $stylesMap = array_map(function($styles, $className) {
            if (empty($styles))
                return '';

            $styles = join(";\n", $styles);
            return ".{$this->className} .{$className} {\n{$styles};\n}\n";
        }, $stylesTable, array_keys($stylesTable));

        return $result . "\n" . join("\n\n", $stylesMap);
    }

    /**
     * @param $characterStyleOverrides
     * @return array
     */
    private function _buildSpans($characterStyleOverrides): array
    {
        $children = [];

        $currentStyle = 0;
        $currentSlice = '';
        $text = $this->text;
        for ($i = 0; $i < count($characterStyleOverrides); $i++) {
            $styleIndex = $characterStyleOverrides[$i];
            if ($styleIndex != $currentStyle && !empty($currentSlice)) {
                $children[] = new TextSpan(self::overrideIndex2ClassName($currentStyle), $currentSlice);
                $currentSlice = '';
            }
            $currentSlice .= $text[$i];
            $currentStyle = $styleIndex;
        }

        if (!empty($currentSlice)) {
            $children[] = new TextSpan(self::overrideIndex2ClassName($currentStyle), $currentSlice);
        }

        if (count($characterStyleOverrides) < strlen($text)) {
            $textLeft = substr(
                $text,
                count($characterStyleOverrides),
                (strlen($text) - count($characterStyleOverrides))
            );
            $children[] = new TextSpan('', $textLeft);
        }

        return $children;
    }

}
