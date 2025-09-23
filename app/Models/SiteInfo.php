<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'abn',
        'tax_agent',
        'facebook',
        'instagram',
        'x',
    ];
}
