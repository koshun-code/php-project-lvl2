<?php

namespace Differ\Formatter;

function formatter($data, $type)
{
    switch ($type) {
        case 'json':
            return 'json';
        case 'plain':
            return 'plain';
        case 'stylish':
            return 'sytlish';
        default:
            throw new \Exception('Not support type');
    }
}