<?php

require_once 'vendor/autoload.php';

use FignelTestAssignment\FigmaJSONParser;
use FignelTestAssignment\Helper\View;

class Main {

    private $html;
    private $css;

    private bool $showSources = FALSE;

    private FigmaJSONParser $figmaJsonParser;

    const ROOT = NULL;

    public function __construct()
    {
        $this->figmaJsonParser = new FigmaJSONParser();
    }

    /**
     * @return Main
     */
    public function showSources():Main
    {
        $this->showSources = TRUE;
        return $this;
    }

    /**
     * @return mixed
     */
    public function render($elementId = self::ROOT)
    {
        $root = $this->figmaJsonParser->getRoot();
        if ($elementId !== self::ROOT) {
            $root = $this->figmaJsonParser
                ->getRoot()
                ->getNodeById($elementId);
        }

        $this->setCss($root->getElement()->css());
        $this->setHtml($root->getElement()->html());

        if ($this->showSources) {
            $this->setHtml($this->getHtml() . $this->sourcesSectionHtml());
        }

        View::render('index', [
            'css' => $this->getCss(),
            'html' => $this->getHtml()
        ]);
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    private function sourcesSectionHtml():string
    {
        $dom = new DOMDocument();

        $dom->preserveWhiteSpace = false;
        $dom->loadHTML($this->getHtml(),LIBXML_HTML_NOIMPLIED);
        $dom->formatOutput = true;

        $html = htmlentities($dom->saveXML($dom->documentElement));

        return View::render('sources', [
            'css' => $this->getCss(),
            'html' => $html
        ], TRUE);
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html): void
    {
        $this->html = $html;
    }

    /**
     * @return mixed
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param mixed $css
     */
    public function setCss($css): void
    {
        $this->css = $css;
    }

}

