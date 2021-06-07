<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;

function genDiff($filePath1, $filePath2, $format = 'stylish')
{
    $file1 = getFileContent($filePath1);
    $file2 = getFileContent($filePath2);
    $content1 = parse($file1);
    $content2 = parse($file2);
    return $content1;
}
//var_dump(genDiff('bin/file1.json', 'bin/file2.json'));
function getFileContent($path)
{
    if (!file_exists($path)) {
        throw new \Exception('no such file');
    }

    $content = file_get_contents($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    return [$content, $extension];
}

function makeTree($key, $type, $newValue, $oldValue, $format)
{
    return [
        'key' => $key,
        'type' => $type,
        'newValue' => $newValue,
        'oldValue' => $oldValue,
        'format' => $format
    ];
}