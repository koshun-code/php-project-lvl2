<?php

namespace Differ\Formatter\FormatStylish;

use function Funct\Collection\flattenAll;

function formatStylish($tree)
{
    $iter = function (array $tree, int $depth) use (&$iter): array {
        return array_map(function($node) use ($depth, $iter) {
            [
                'key' => $key,
                'type' => $type,
                'newValue' => $newValue,
                'oldValue' => $oldValue,
                'children' => $children
            ] = $node;
            
            $indent = makeIndent($depth - 1);
            
            switch ($type) {
                case 'complex':
                    $indentAfter = makeIndent($depth);
                    return ["{$indent}    {$key}: {", $iter($children, $depth + 1), "{$indentAfter}}"];
                case 'added':
                    $prepareNewValue = prepareValue($newValue, $depth);
                    return "{$indent}  + {$key}: {$prepareNewValue}";
                case 'removed':
                    $prepareOldValue = prepareValue($oldValue, $depth);
                    return "{$indent}  - {$key}: {$prepareOldValue}";
                case 'unchanged':
                    $prepareNewValue = prepareValue($newValue, $depth);
                    return "{$indent}    {$key}: {$prepareNewValue}";
                case 'update':
                    $prepareOldValue = prepareValue($oldValue, $depth);
                    $prepareNewValue = prepareValue($newValue, $depth);
                    $addedLines = "{$indent}  + {$key}: {$prepareNewValue}";
                    $deletedLines = "{$indent}  - {$key}: {$prepareOldValue}";
                    return join("\n", [$deletedLines, $addedLines]);
                default:
                    throw new \Exception("{$type} is no supported");
            }
        }, $tree);
    };
    return join("\n", flattenAll(['{', $iter($tree, 1), '}']));
}

function prepareValue($value, $depth)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (!is_object($value)) {
        return $value;
    }

    $keys = array_keys(get_object_vars($value));
    $indent = makeIndent($depth);

    $lines = array_map(function($key) use ($value, $depth, $indent) {
        $childrenValue = prepareValue($value->$key, $depth + 1);
            return "{$indent}    {$key}: {$childrenValue}";
    }, $keys);

    $prepareValue = join("\n", $lines);
    return "{\n{$prepareValue}\n{$indent}}";
}

function makeIndent(int $depth)
{
    return str_repeat(" ", 4 * $depth);
}
