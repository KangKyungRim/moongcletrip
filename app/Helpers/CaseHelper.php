<?php
namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class CaseHelper
{
    // ↓↓↓ 타입힌트/리턴타입 제거 (PHP 7.4 호환)
    public static function camelKeys($value)
    {
        // Eloquent Model / Collection 등
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        } elseif ($value instanceof JsonSerializable) {
            $value = $value->jsonSerialize();
        } elseif (is_object($value)) {
            // stdClass 등 일반 객체도 방어적으로 처리
            $value = (array) $value;
        }

        if (is_array($value)) {
            $new = [];
            foreach ($value as $k => $v) {
                $new[is_string($k) ? self::toCamel($k) : $k] = self::camelKeys($v);
            }
            return $new;
        }

        return $value;
    }

    private static function toCamel($key)
    {
        if ($key === '' || strpos($key, '_') === false) return $key;
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
        // curation_idx -> curationIdx
    }
}
