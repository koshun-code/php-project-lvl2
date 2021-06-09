<?php

namespace Differ\Formatter\FormatJson;

function formatJson($data)
{
    return json_encode($data, JSON_THROW_ON_ERROR);
}