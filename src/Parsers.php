<?php

namespace Differ\Parsers;

function parse($data)
{
    return json_decode($data);
}