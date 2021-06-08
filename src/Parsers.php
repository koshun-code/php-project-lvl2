<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $type)
{
    switch ($type) {
        case 'json' :
            return json_decode($data);
        case 'yml':
        case 'yaml':
            return Yaml::parse($data,  Yaml::PARSE_OBJECT_FOR_MAP);
        default: 
            throw new \Exception("Not supported {$type} Extension");
    }
}