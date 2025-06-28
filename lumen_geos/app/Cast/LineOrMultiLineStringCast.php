<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;

class LineOrMultiLineStringCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        $geometry = Geometry::fromWkt($value);

        if ($geometry instanceof LineString || $geometry instanceof MultiLineString) {
            return $geometry;
        }

        throw new InvalidArgumentException('Expected LineString or MultiLineString, ' . get_class($geometry) . ' given.');
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value->toWkt();
    }
}
