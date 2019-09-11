<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisMobil extends Model
{
    public function mobils()
    {
        return $this->hasMany("App\Mobil");
    }

    public function bbm()
    {
        return $this->belongsTo("App\Bbm");
    }
}
