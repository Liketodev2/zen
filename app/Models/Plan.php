<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    public function options()
    {
        return $this->hasMany(PlanOption::class);
    }
}
