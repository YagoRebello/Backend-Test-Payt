<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    public function getCodeAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }

    public static function findByCode($code)
    {
        $id = Hashids::decode($code);
        if (!empty($id)) {
            return self::find($id[0]);
        }
        return null;
    }
}