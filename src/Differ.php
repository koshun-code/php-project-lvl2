<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatter\formatter;
use function Funct\Collection\sortBy;
use function Funct\Collection\union;

function genDiff($filePath1, $filePath2, $format = 'stylish')
{
    $file1 = getFileContent($filePath1);
    $file2 = getFileContent($filePath2);
    $content1 = parse($file1[0], $file1[1]);
    $content2 = parse($file2[0], $file2[1]);

    $tree = getTree($content1, $content2);
    //dump($tree);
    return formatter($tree, $format);
}

function getTree(object $data1, object $data2): array
{
    $keysData1 = array_keys(get_object_vars($data1));
    $keysData2 = array_keys(get_object_vars($data2));
    $unionKeys = union($keysData1, $keysData2);
    $sortedKeys = array_values(sortBy($unionKeys, fn($key) => $key));
    
    $tree = array_map(function($key) use($data1, $data2) {
        if (!property_exists($data1, $key)) {
            return makeTree($key, 'added', $data2->$key, null);
        }
        if (!property_exists($data2, $key)) {
            return makeTree($key, 'removed', null, $data1->$key);
        }
        if (is_object($data1->$key) && is_object($data2->$key)) {
            return maketree($key, 'complex', null, null, getTree($data1->$key, $data2->$key));
        }
        if ($data1->$key === $data2->$key) {
            return makeTree($key, 'unchanged', $data1->$key, $data2->$key);
        }
        return makeTree($key, 'updated', $data2->$key, $data1->$key);
    }, $sortedKeys);
    return $tree;
}

function getFileContent($path)
{
    if (!file_exists($path)) {
        throw new \Exception("Can't find file in {$path}");
    }

    $content = file_get_contents($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    return [$content, $extension];
}

function makeTree($key, $type, $newValue, $oldValue, $children = null)
{
    return [
        'key' => $key,
        'type' => $type,
        'newValue' => $newValue,
        'oldValue' => $oldValue,
        'children' => $children
    ];
}