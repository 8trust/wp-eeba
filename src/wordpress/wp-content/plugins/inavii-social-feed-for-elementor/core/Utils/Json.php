<?php

namespace Inavii\Instagram\Utils;

class Json
{
    public static function decode(string $json)
    {
        $result = \json_decode($json, true);

        if (\json_last_error() === JSON_ERROR_NONE) {

            if (isset($result['data']) && is_array($result['data'])) {
                return $result['data'];
            }

            return $result;
        }
    }
}
