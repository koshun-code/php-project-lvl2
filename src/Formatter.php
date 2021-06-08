<?php

namespace Differ\Formatter;
use function Differ\Formatter\FormatStylish\formatStylish;
use function Differ\Formatter\FormatJson\formatJson;
use function Differ\Formatter\FormatPlain\formatPlain;

function formatter($data, $type)
{
    switch ($type) {
        case 'json':
            return formatJson($data);
        case 'plain':
            return formatPlain($data);
        case 'stylish':
            return formatStylish($data);
        default:
           throw new \Exception("Sorry, format not support");
    }
}