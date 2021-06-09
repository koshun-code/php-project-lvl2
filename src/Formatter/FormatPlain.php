<?php

namespace Differ\Formatter\FormatPlain;

use function Funct\Collection\flattenAll;
use Exception;


function formatPlain(array $tree)
{
    $iter = function(array $tree, array $ancestors) use (&$iter) {
        return array_map(function($node) use ($ancestors, $iter) {
            [
                'key' => $key,
                'type' => $type,
                'newValue' => $newValue,
                'oldValue' => $oldValue,
                'children' => $children
            ] = $node;
            
            $pathToProperty = implode('.', [...$ancestors, $key]);

            switch ($type) {
                case 'complex':
                    return $iter($children, [...$ancestors, $key]);
                case 'added':
                    $preparedNewValue = setString($newValue);
                    return "Property '{$pathToProperty}' was added with value: {$preparedNewValue}";
                case 'removed':
                    return "Property '{$pathToProperty}' was removed";
                case 'unchanged':
                    return [];
                case 'update':
                    $preparedOldValue = setString($oldValue);
                    $preparedNewValue = setString($newValue);
                    return "Property '{$pathToProperty}' was updated. From {$preparedOldValue} to {$preparedNewValue}";
                default:
                    throw new \Exception("This type: {$type} is not supported.");
            }

        }, $tree);
    };
   return  implode("\n", flattenAll($iter($tree, [])));
}

function setString($value)
{
    
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_object($value)) {
        return '[complex value]';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return (string) $value;
    
}
