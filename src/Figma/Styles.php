<?php

namespace FignelTestAssignment\Figma;

use FignelTestAssignment\Helper\Arr;
use FignelTestAssignment\Helper\Utils;

class Styles
{
    private static array $textDecorationMap = [
        'STRIKETHROUGH' => 'line-through',
        'UNDERLINE' => 'underline'
    ];

    public static function build($data):array
    {
        $result = [
            'width' => self::parseWidth($data),
            'height' => self::parseHeight($data),
            'font-family' => self::parseFontFamily($data),
            'font-style' => self::parseFontStyle($data),
            'font-weight' => Arr::get($data, 'fontWeight'),
            'font-size' => self::parseFontSize($data),
            'letter-spacing' => self::parseLetterSpacing($data),
            'line-height' => self::parseLineHeight($data),
            'text-decoration' => self::getTextDecoration($data),
            'text-align' => self::parseTextAlign($data),
            'vertical-align' => self::parseTextAlignVertical($data),
            'opacity' => self::parseOpacity($data),
            'color' => self::parseColor($data),
            'background-color' => self::parseBackgroundColor($data),
            'background-blend-mode' => self::parseBlendMode($data),
        ];

        self::textAutoResize($data, $result);

        // remove empty values
        $result = array_filter($result, fn($value) => !!$value);

        $result = array_map(fn($value, $name) => "$name: $value", $result, array_keys($result));

        // apply element styles
        if ($data['style'] ?? NULL) {
            $result = [...$result, ...self::build($data['style'])];
        }

        return $result;
    }

    private static function parseLetterSpacing($data):?string
    {
        return Styles::pxValue(Arr::get($data , 'letterSpacing'));
    }

    private static function parseFontStyle($data):?string
    {
        return Arr::get($data, 'italic') ? 'italic' : 'normal';
    }

    private static function parseTextAlign($data):?string
    {
        $textAlignHorizontal = Arr::get($data, 'textAlignHorizontal');
        return $textAlignHorizontal
            ? strtolower($textAlignHorizontal)
            : NULL;
    }

    private static function parseBackgroundColor($data):?string
    {
        $value = Arr::get($data, 'backgroundColor');
        return !is_null($value)
            ? Utils::color2Rgba($value)
            : NULL;
    }

    private static function parseBlendMode($data)
    {
        // PASS_THROUGH, NORMAL => normal
        // LINEAR_BURN, COLOR_BURN => color-burn
        // LINEAR_DODGE, COLOR_DODGE => color-dodge

        $value = Arr::get($data, 'blendMode');

        if (is_null($value))
            return NULL;

        return str_replace(
            ['_', 'linear', 'pass-through'],
            ['-', 'color', 'normal'],
            strtolower($value)
        );
    }

    private static function getTextDecoration($data): ?string
    {
        $textDecoration = Arr::get($data, 'textDecoration');
        if (!$textDecoration || !Arr::has(self::$textDecorationMap, $textDecoration))
            return NULL;

        return self::$textDecorationMap[$textDecoration];
    }

    private static function parseOpacity($data): ?string
    {
        $opacity = Arr::get($data, 'fills.0.opacity');
        return !is_null($opacity)
            ? round($opacity, 2)
            : NULL;
    }

    private static function parseColor($data): ?string
    {
        $color = Arr::get($data, 'fills.0.color');
        return !is_null($color)
            ? Utils::color2Rgba($color)
            : NULL;
    }

    private static function parseTextAlignVertical($data): ?string
    {
        $textAlignVertical = Arr::get($data, 'textAlignVertical');
        return $textAlignVertical
            ? strtolower($textAlignVertical)
            : NULL;
    }

    private static function parseFontFamily($data):string
    {
        $result = '';
        if ($data['fontFamily'] ?? NULL) {
            $result = $data['fontFamily'] . ' ' . ($data['fontPostScriptName'] ?? '');
        }
        return $result;
    }

    private static function parseWidth($data):?string
    {
        return Styles::pxValue(Arr::get($data , 'absoluteBoundingBox.width'));
    }

    private static function parseHeight($data):?string
    {
        return Styles::pxValue(Arr::get($data , 'absoluteBoundingBox.height'));
    }

    private static function parseFontSize($data):?string
    {
        return Styles::pxValue(Arr::get($data , 'fontSize'));
    }

    private static function pxValue($value):?string
    {
        return !is_null($value) ? "{$value}px" : NULL;
    }

    /**
     * @param $paragraphSpacing
     * @return string[]
     */
    public static function paragraphSpacingStyles($paragraphSpacing): array
    {
        return [ "margin-bottom: {$paragraphSpacing}px" ];
    }

    private static function parseLineHeight($data): ?string
    {
        $lineHeightValue = Arr::get($data, 'lineHeightPx');
        $lineHeightUnit = Arr::get($data, 'lineHeightUnit');

        if (empty($lineHeightUnit))
        {
            if (!empty($lineHeightValue)) {
                return $lineHeightValue . 'px';
            }
            return NULL;
        }

        if ($lineHeightUnit === 'AUTO')
            return 'auto';

        $isPercent = $lineHeightUnit === 'INTRINSIC_%';
        $lineHeightUnit = $isPercent ? '%' : 'px';

        $lineHeightValue = $isPercent
            ? $data['lineHeightPercent']
            : $data['lineHeightPx'];

        $lineHeightValue = round($lineHeightValue, 2);

        return "{$lineHeightValue}{$lineHeightUnit}";
    }

    /**
     * @param $data
     * @param array $result
     * @return void
     */
    private static function textAutoResize($data, array &$result):void
    {
        if (!Arr::has($data, 'textAutoResize'))
            return;

        $textAutoResize = Arr::get($data, 'textAutoResize');

        if ($textAutoResize == 'NONE')
            return;

        if ($textAutoResize == 'HEIGHT') {
            $result['height'] = NULL;
        }

        if ($textAutoResize == 'WIDTH_AND_HEIGHT') {
            $result['min_width'] = NULL;
            $result['width'] = NULL;
            $result['height'] = NULL;
        }

        if ($textAutoResize == 'TRUNCATE') {
            $result['overflow'] = 'hidden';
            $result['text-overflow'] = 'ellipsis';
        }
    }

}
