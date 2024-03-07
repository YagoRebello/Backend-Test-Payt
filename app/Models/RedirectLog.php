<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RedirectLog extends Model
{
    use HasFactory;
    protected $fillable = ['ip', 'user_agent', 'referer', 'query_params', 'redirect_id'];

    public function redirect()
    {
        return $this->belongsTo(Redirect::class);
    }
}

