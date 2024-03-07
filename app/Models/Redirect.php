<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;

class Redirect extends Model
{
    use HasFactory;

    protected $table = 'redirects';
    protected $fillable = ['url_destino', 'status', 'code'];
    protected $appends = ['code'];

    public function getRouteKeyName()
    {
        return 'code';
    }
    public function redirectLogs()
    {
        return $this->hasMany(RedirectLog::class);
    }
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (!$model->code) {
                $hashids = new Hashids(config('app.key'), 8);
                $model->code = $hashids->encode($model->id);
            }
        });
    }
}
